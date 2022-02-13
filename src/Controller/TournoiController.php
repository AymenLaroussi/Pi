<?php

namespace App\Controller;

use App\Entity\Tournoi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TournoiType;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends AbstractController
{
    /**
     * @Route("/tournoi", name="tournoi")
     */
    public function index(): Response
    {
       /* return $this->render('tournoi/index.html.twig', [
            'controller_name' => 'TournoiController',
        ]);*/
        $tournois= $this->getDoctrine()
            ->getRepository(Tournoi::class)->findAll();
        return $this->render("tournoi/index.html.twig",
            array("tournois"=>$tournois));
    }
    /**
     * @Route("/addtournoi", name="addtournoi")
     */
    public function addTournoi(Request $request)
    {
        $tournoi=new Tournoi();

        $form= $this->createForm(TournoiType::class,$tournoi);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            
            $em= $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();
            return $this->redirectToRoute("tournoi");
        }
        return $this->render("tournoi/add-tournoi.html.twig",array("formTournoi"=>$form->createView()));
    }
    /**
     * @Route("/showTournoi{id}",name="showTournoi")
     */
    public function show($id){
        $tournoi= $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        return $this->render("tournoi/tournament-details.html.twig",array("tournoi"=>$tournoi));
    }
    /**
     * @Route("/removeTournoi{id}",name="removeTournoi")
     */
    public function delete($id){
        $tournoi= $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($tournoi);
        $em->flush();
        return $this->redirectToRoute("tournoi");
    }

}
