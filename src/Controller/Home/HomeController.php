<?php

namespace App\Controller\Home;

use App\Entity\Admin\Messages;
use App\Entity\Car;
use App\Form\Admin\MessagesType;
use App\Repository\CarRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
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
        $slider = $carRepository->findBy([], ['title' => 'ASC'], 6);
        $cars = $carRepository->findBy([], [], 10);
        $newcars = $carRepository->findBy([], ['title' => 'DESC'], 5);
        $category = $categoryRepository->findAll();
       // dump($category);
       // die();
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
    public function show(Car $car,$id,ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findBy(['car' => $id]);
        return $this->render('home/SinglePages/CarShow.html.twig', [
            'car' => $car,
            'images'=> $images
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
                $message->setIp($_SERVER['REMOTE_ADDR']);
                $entityManager->persist($message);
                $entityManager->flush();
                $this->addFlash('success', 'Your message has been sent succesfully');


                ///////////////////////Send Mail
                $email = (new Email())
                    ->from($settings[0]->getSmtpemail())
                    ->to($form['email']->getData())
                    ->subject('Thank you for message')
                    ->html("Dear " . $form['name']->getData() . "<br>
                        <p>We have received your message</p> Thank you<br>
                            ============================================
                        <br>" . $settings[0]->getCompany() . "<br> Address:" . $settings[0]->getAddress() . "<br> Phone:" . $settings[0]->getPhone() . "<br>"
                    );
                $transport = new GmailTransport($settings[0]->getSmtpemail(),$settings[0]->getSmtppassword());
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
