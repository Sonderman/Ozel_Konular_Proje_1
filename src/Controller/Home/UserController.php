<?php

namespace App\Controller\Home;

use App\Entity\Admin\Comment;
use App\Entity\Contract;
use App\Entity\User;
use App\Form\Admin\CommentType;
use App\Form\User2Type;
use App\Repository\Admin\CommentRepository;
use App\Repository\ContractRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('home/user/show.html.twig');

    }

    /**
     * @Route("/comments", name="user_comments", methods={"GET"})
     */
    public function comments(CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();
        $comments=$commentRepository->getUserComments($user->getId());
        return $this->render('home/user/comments.html.twig',[
            'comments' => $comments,
        ]);

    }

    /**
     * @Route("/contracts", name="user_contracts", methods={"GET"})
     */
    public function contracts(ContractRepository $contractRepository): Response
    {
        $user = $this->getUser();
        $contracts = $contractRepository->getContractsForUser($user->getId());
        //dump($contracts);
        //die();
        return $this->render('home/user/mycontracts.html.twig',[
            'contracts'=>$contracts,
        ]);
    }
    /**
     * @Route("/contracts/{id}", name="user_contractshow", methods={"GET"})
     */
    public function contractshow($id,ContractRepository $contractRepository): Response
    {
        $contract = $contractRepository->getContractToShow($id);
        //dump($contract);
        //die();
        return $this->render('home/user/contractshow.html.twig', [
            'contract' => $contract,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var file $file */
            $file =$form['image']->getData();
            if($file){
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                }catch (FileException $e){

                }
                $user->setImage($fileName);
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('home/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('home/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user2,$id,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        if($user->getId() != $id){
            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm(User2Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var file $file */
            $file =$form['image']->getData();
            if($file){
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                }catch (FileException $e){

                }
                $user->setImage($fileName);

                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }

            $entityManager = $this->getDoctrine()->getManager();
            $user2->setUpdatedAt();
            $entityManager->persist($user2);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('home/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
    /**
     * @Route("/newcomment/{id}", name="user_new_comment", methods={"GET","POST"})
     */
    public function newcomment(Request $request,$id): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {

            if ($this->isCsrfTokenValid('comment', $submittedToken)) {

                $entityManager = $this->getDoctrine()->getManager();
                $comment->setStatus('New');
                $comment->setIp($_SERVER['REMOTE_ADDR']);
                $comment->setCarid($id);
                $comment->setUserid($this->getUser()->getId());
                $entityManager->persist($comment);
                $entityManager->flush();
                $this->addFlash('success', 'Your comment has been sent succesfully');

                return $this->redirectToRoute('car_show', ['id' => $id]);
            }
        }

        return $this->redirectToRoute('car_show',['id'=>$id]);
    }
}
