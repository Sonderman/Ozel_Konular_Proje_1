<?php

namespace App\Controller\Home;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('home/CarShow.html.twig', [
            'car' => $car,
        ]);
    }

}
