<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Entity\Image;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/car")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/", name="admin_car_index", methods={"GET"})
     */
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->getAllCars();
        //dump($cars);
        //die();
        return $this->render('admin/car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/new", name="admin_car_new", methods={"GET","POST"})
     */
    public function new(Request $request,ImageRepository $imageRepository): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // resim almak için burasıda eklendi ve services.yaml de de dosya yolu belirtilmeli
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
                $car->setImage($fileName);
            }

            // resim upload kodları burada bitiyor
            $car->setOwnerId(1);
            $car->setCreatedAt(new \DateTime());
            $entityManager->persist($car);
            $entityManager->flush();


            return $this->redirectToRoute('admin_car_index');
        }

        return $this->render('admin/car/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="admin_car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('admin/car/show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_car_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Car $car): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dosya ekleme işlemi için
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
                $car->setImage($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $car->setUpdatedAt();
            $entityManager->persist($car);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_car_index');
        }

        return $this->render('admin/car/edit.html.twig', [
            'car' => $car,
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
     * @Route("/{id}", name="admin_car_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Car $car): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_car_index');
    }
}
