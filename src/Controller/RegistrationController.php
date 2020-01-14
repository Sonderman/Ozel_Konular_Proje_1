<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use App\Security\MyAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints\Date;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, MyAuthenticator $authenticator): Response
    {
        $user = new User();
        $previousRoute = $request->headers->get('referer');
        $submittedToken = $request->request->get('token');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() ) {

            if ($this->isCsrfTokenValid('form-message', $submittedToken)) {
                /** @var file $file */
                $file = $form['image']->getData();
                if ($file) {
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    try {
                        $file->move(
                            $this->getParameter('image_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {

                    }
                    $user->setImage($fileName);
                }
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'You are succesfully registered');

                // do anything else you need here, like send an email

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                ) ?: new RedirectResponse('/');
            }
        }

            return $this->render('registration/userregister.html.twig', [
                'registrationForm' => $form->createView(),
            ]);

    }
    /**
     * @Route("/registeradmin", name="admin_register")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, MyAuthenticator $authenticator): Response
    {
        $user = new User();
        $previousRoute = $request->headers->get('referer');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var file $file */
            $file =$form['image']->getData();
            if($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $user->setImage($fileName);
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $user->setUpdatedAt();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            ) ? : new RedirectResponse('/');
        }

            return $this->render('registration/adminregister.html.twig', [
                'registrationForm' => $form->createView(),
            ]);

    }

    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
}
