<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
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
     * @Route("/", name="car_index", methods={"GET"})
     */
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('admin/car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="car_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtention();
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
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('car_index');
        }

        return $this->render('admin/car/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('admin/car/show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="car_edit", methods={"GET","POST"})
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
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtention();
                try{
                    $file->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                }catch (FileException $e){

                }
                $car->setImage($fileName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('car_index');
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
     * @Route("/{id}", name="car_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Car $car): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('car_index');
    }
}