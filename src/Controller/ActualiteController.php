<?php

namespace App\Controller;
use App\Repository\EvenementRepository;
use App\Repository\SponsorsRepository;
use App\Entity\Evenement;
use App\Entity\Sponsors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ActualiteController extends AbstractController
{
    /**
     * @Route("/actualite", name="actualite")
     */
    public function index(EvenementRepository $evenementsRepository): Response
    {
       
        return $this->render('actualite/index.html.twig', 
            [
                'evenements' => $evenementsRepository->findAll(),
            ]);
        

    }
 
}
