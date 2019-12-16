<?php

namespace App\Controller\Home;

use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
 * @Route("/", name="home")
 */
    public function index(SettingsRepository $settingsRepository)
    {
        $data = $settingsRepository->findBy(['id'=>1]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data'=>$data,
        ]);
    }


}
