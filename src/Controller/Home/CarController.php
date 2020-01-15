<?php

namespace App\Controller\Home;

use App\Entity\Car;
use App\Form\Car1Type;
use App\Form\CarType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/cars")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/", name="user_car_index", methods={"GET"})
     */
    public function index(CarRepository $carRepository): Response
    {
        $user = $this->getUser();
        $cars = $carRepository->getMyContractedCars($user->getId());
        //dump($cars);
        //die();
        return $this->render('home/car/index.html.twig', [
            //'cars' => $carRepository->findby(['owner_id'=>$user->getId()]),
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/{id}", name="user_car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        //dump($car);
        //die();
        return $this->render('home/car/show.html.twig', [
            'car' => $car,
        ]);
    }
}
