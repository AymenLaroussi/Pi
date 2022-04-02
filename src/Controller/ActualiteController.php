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
    public function index(EvenementRepository $evenementsRepository,SponsorsRepository $sponsorsRepository): Response
    {
        $evenements =$evenementsRepository->findAll();
        $sponsors =$sponsorsRepository->findAll();
        return $this->render('actualite/index.html.twig',array("evenements"=> $evenements,"sponsors"=> $sponsors));
     }
   
           
     /**
     * @Route("/actualite/{id}", name="voir_details", methods={"GET"})
     */
    public function voir(int $id): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        return $this->render('actualite/event.html.twig', [
            'evenements' => $evenements,
            'sponsors' => $evenements->getSponsors()
        ]);
    }

    
 
}
