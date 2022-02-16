<?php

namespace App\Controller;

use App\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JeuController extends AbstractController
{
    /**
     * @Route("/jeu", name="jeu")
     */

    public function index(): Response
    {
        /* return $this->render('tournoi/index.html.twig', [
             'controller_name' => 'TournoiController',
         ]);*/

            $jeux= $this->getDoctrine()
                ->getRepository(Jeu::class)->findAll();

            return $this->render("jeu/index.html.twig",
                array("jeux"=>$jeux));
        }


}
