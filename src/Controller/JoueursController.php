<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueursController extends AbstractController
{
    /**
     * @Route("/joueurs", name="joueurs")
     */
    public function index(): Response
    {
        return $this->render('joueurs/index.html.twig', [
            'controller_name' => 'JoueursController',
        ]);
    }
}
