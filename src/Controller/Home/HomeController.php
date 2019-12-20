<?php

namespace App\Controller\Home;

use App\Entity\Admin\Messages;
use App\Entity\Car;
use App\Form\Admin\MessagesType;
use App\Repository\CarRepository;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
 * @Route("/", name="home")
 */
    public function index(SettingsRepository $settingsRepository,CarRepository $carRepository)
    {
        $data = $settingsRepository->findBy(['id'=>1]);
        $slider = $carRepository->findBy([],['title'=>'ASC'],5);
        //dump($slider);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data'=>$data,
            'slider'=>$slider
        ]);
    }
    /**
     * @Route("/cars/{id}", name="car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('home/SinglePages/CarShow.html.twig', [
            'car' => $car,
        ]);
    }
    /**
     * @Route("/aboutus", name="aboutus")
     */
    public function aboutus(SettingsRepository $settingsRepository): Response
    {
        $settings = $settingsRepository->findBy(['id'=>1]);
        return $this->render('home/SinglePages/aboutus.html.twig', [
            'settings' => $settings,
        ]);
    }
    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function contact(SettingsRepository $settingsRepository,Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($form->isSubmitted()) {

            if($this->isCsrfTokenValid('form-message',$submittedToken)){
            $entityManager = $this->getDoctrine()->getManager();
            $message->setStatus('New');
            $message->setIp($_SERVER['REMOTE_ADDR']);
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success','Your message has been sent succesfully');
            return $this->redirectToRoute('contact');
            }
        }

        $settings = $settingsRepository->findBy(['id'=>1]);
        return $this->render('home/SinglePages/contact.html.twig', [
            'settings' => $settings,
            'form' => $form->createView(),
        ]);
    }

}
