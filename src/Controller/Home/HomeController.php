<?php

namespace App\Controller\Home;

use App\Entity\Admin\Messages;
use App\Entity\Car;
use App\Entity\Contract;
use App\Entity\User;
use App\Form\Admin\MessagesType;
use App\Form\ContractType;
use App\Repository\Admin\CommentRepository;
use App\Repository\CarRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\SettingsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SettingsRepository $settingsRepository, CarRepository $carRepository,CategoryRepository $categoryRepository)
    {
        $data = $settingsRepository->findBy(['id' => 1]);
        $slider = $carRepository->findBy([], ['title' => 'ASC'], 7);
        $cars = $carRepository->findAll();
        $newcars = $carRepository->findBy([], ['title' => 'DESC'], 5);
        $category = $categoryRepository->findAll();
        //dump($slider);
        //die();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data,
            'slider' => $slider,
            'cars'=>$cars,
            'newcars'=>$newcars,
            'category'=>$category
        ]);
    }

    /**
     * @Route("/cars/{id}", name="car_show", methods={"GET"})
     */
    public function show(Car $car,$id,ImageRepository $imageRepository,CommentRepository $commentRepository,UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $images = $imageRepository->findBy(['car' => $id]);
        $comments = $commentRepository->findBy(['carid' => $id,'status'=>'True']);
        //dump($images);
        //die();
        return $this->render('home/SinglePages/CarShow.html.twig', [
            'users' => $users,
            'car' => $car,
            'images'=> $images,
            'comments'=>$comments
        ]);
    }
    /**
     * @Route("/contractform/{id}", name="contract_form", methods={"GET","POST"})
     */
    public function contractform(Car $car,$id,Request $request,ImageRepository $imageRepository,UserRepository $userRepository): Response
    {
        //dump($car);
       // die();
        $contract = new Contract();
        $user = $this->getUser();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($form->isSubmitted()&& $this->isCsrfTokenValid('form-contract', $submittedToken)) {
            $entityManager = $this->getDoctrine()->getManager();
            //dump($form);
            //die();
            $contract->setCarId($id);
            $contract->setCustomerId($this->getUser()->getId());
            $contract->setStatus("New");
            $entityManager->persist($contract);
            $entityManager->flush();
            $this->addFlash('success', 'Succesfull');

            $entityManager = $this->getDoctrine()->getManager();
            $car->setOwnerId($user->getId());
            $car->setUpdatedAt();
            $car->setContractId($contract->getId());
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('contract_form',['id'=>$id]);
        }


        $users = $userRepository->findAll();
        $images = $imageRepository->findBy(['car' => $id]);

        return $this->render('home/SinglePages/ContractForm.html.twig', [
            'form'=> $form->createView(),
            'car' => $car,
            'users' => $users,
            'images'=> $images,
        ]);
    }

    /**
     * @Route("/aboutus", name="aboutus")
     */
    public function aboutus(SettingsRepository $settingsRepository): Response
    {
        $settings = $settingsRepository->findBy(['id' => 1]);
        return $this->render('home/SinglePages/aboutus.html.twig', [
            'settings' => $settings,
        ]);
    }
    /**
     * @Route("/cargallery", name="gallery")
     */
    public function cargallery(CarRepository $carRepository,CategoryRepository $categoryRepository,SettingsRepository $settingsRepository): Response
    {
        $cars = $carRepository->findAll();
        $category = $categoryRepository->findAll();
        $settings = $settingsRepository->findBy(['id' => 1]);
        return $this->render('home/SinglePages/CarGallery.html.twig', [
            'settings' => $settings,
            'cars' => $cars,
            'category'=>$category
        ]);
    }

    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function contact(SettingsRepository $settingsRepository, Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        $settings = $settingsRepository->findBy(['id' => 1]);
        if ($form->isSubmitted()) {

            if ($this->isCsrfTokenValid('form-message', $submittedToken)) {
                $entityManager = $this->getDoctrine()->getManager();
                $message->setStatus('New');
                $message->setCreatedAt(new \DateTime());
                $message->setIp($_SERVER['REMOTE_ADDR']);
                $entityManager->persist($message);
                $entityManager->flush();
                $this->addFlash('success', 'Your message has been sent succesfully');


                ///////////////////////Send Mail/////////////////////////
                $email = (new Email())
                    ->from($settings[0]->getSmtpemail())
                    ->to($form['email']->getData())
                    ->subject('Thank you for message')
                    ->html("Dear " . $form['name']->getData() . "<br>
                        <p>We have received your message</p> Thank you<br>
                            ============================================
                        <br>" . $settings[0]->getCompany() . "<br> Address:" . $settings[0]->getAddress() . "<br> Phone:" . $settings[0]->getPhone() . "<br>"
                    );
                $transport = new GmailSmtpTransport($settings[0]->getSmtpemail(),$settings[0]->getSmtppassword());
                $mailer = new  Mailer($transport);
                $mailer->send($email);


                return $this->redirectToRoute('contact');
            }
        }


        return $this->render('home/SinglePages/contact.html.twig', [
            'settings' => $settings,
            'form' => $form->createView(),
        ]);
    }

}
