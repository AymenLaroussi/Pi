<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Tournoi;
use App\Entity\Jeu;
use App\Entity\User;
use App\Repository\TournoiRepository;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
if($this->getUser()){
    $tournois= $this->getDoctrine()
        ->getRepository(Tournoi::class)->findAll();
    $mestournois=$this->getDoctrine()
        ->getRepository(Tournoi::class)->listTournoiByUser($this->getUser()->getUsername());
    $jeux= $this->getDoctrine()
        ->getRepository(Jeu::class)->findAll();
    return $this->render("tournoi/index.html.twig",
        array("tournois"=>$tournois,"mestournois"=>$mestournois,"jeux"=>$jeux));
}
        return $this->redirectToRoute("connexion");
    }
    /**
     * @Route("/addtournoi", name="addtournoi")
     */
    public function addTournoi(Request $request)
    {
        $tournoi=new Tournoi();
        $user2=$this->getUser();
//
//
//

        $form= $this->createForm(TournoiType::class,$tournoi);
        $tournoi->setOrganisteur($user2);
        $form->handleRequest($request);
        $type="ajouter";
        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $tournoi->setImage($newFilename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();

            for ($i =1; $i <= $tournoi->getNbrEquipes(); $i++) {
                $equipe{$i} = new Equipe;
                $equipe{$i}->setLabel("equipe{$i}");
                $equipe{$i}->setTournoi($tournoi);
                $em->persist($equipe{$i});
                $em->flush();
            }
                return $this->redirectToRoute("tournoi");

        }
        return $this->render("tournoi/add-tournoi.html.twig",array("formTournoi"=>$form->createView(),"type"=>$type));
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
    /**
     * @Route("/tournoiUpdate/{id}",name="tournoiUpdate")
     */
    public function updateTournoi(TournoiRepository $s,$id,Request $request)
    {
        $tournoi= $s->find($id);
        //var_dump($student).die();
        $formTournoi= $this->createForm(TournoiType::class,$tournoi);
        $formTournoi->handleRequest($request);
        $type="modifier";
        if($formTournoi->isSubmitted() && $formTournoi->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("tournoi");
        }
        return $this->render("tournoi/add-tournoi.html.twig",array("formTournoi"=>$formTournoi->createView(),"type"=>$type,"tournoi"=>$tournoi));
    }


}
