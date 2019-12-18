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
        return $this->render('home/car/index.html.twig', [
            'cars' => $carRepository->findby(['owner_id'=>$user->getId()]),
        ]);
    }

    /**
     * @Route("/new", name="user_car_new", methods={"GET","POST"})
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

            $user = $this->getUser();
            $car->setOwnerId($user->getId());
            $car->setStatus("New");
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('user_car_index');
        }

        return $this->render('home/car/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('home/car/show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_car_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Car $car): Response
    {
        $form = $this->createForm(Car1Type::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_car_index');
        }

        return $this->render('home/car/edit.html.twig', [
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
     * @Route("/{id}", name="user_car_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Car $car): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_car_index');
    }
}
