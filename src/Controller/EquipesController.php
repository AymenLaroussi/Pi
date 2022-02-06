<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipesController extends AbstractController
{
    /**
     * @Route("/equipes", name="equipes")
     */
    public function index(): Response
    {
        return $this->render('equipes/index.html.twig', [
            'controller_name' => 'EquipesController',
        ]);
    }
}
