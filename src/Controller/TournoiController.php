<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Tournoi;
use App\Entity\Jeu;
use App\Entity\User;
use App\Repository\TournoiRepository;
use App\Repository\JeuRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;

use phpDocumentor\Reflection\DocBlock\Serializer;
use phpDocumentor\Reflection\Types\Object_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TournoiType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


class TournoiController extends AbstractController
{

    /**
     * @Route("/removeTour/{id}",name="removeTour")
     *
     */
    public function delete(Request $request,$id)
    {

        $tournoi = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tournoi);
        $em->flush();
        return $this->redirectToRoute("tournoi");

    }
    /**
     * @Route("/tournoi", name="tournoi")
     */
    public function index(SerializerInterface $serializerInterface): Response
    {
        /* return $this->render('tournoi/index.html.twig', [
             'controller_name' => 'TournoiController',
         ]);*/

        $tournois = $this->getDoctrine()
            ->getRepository(Tournoi::class)->findAll();
        if($this->getUser()->getUsername()!=null) {
            $mestournois = $this->getDoctrine()
                ->getRepository(Tournoi::class)->listTournoiByUser($this->getUser()->getUsername());
        }
        $jeux = $this->getDoctrine()
            ->getRepository(Jeu::class)->findAll();


//            $tournoisJson=$serializerInterface->serialize($tournois,'json',['groups'=>'tournoi']);
//return New JsonResponse($tournoisJson) ;


            return $this->render("tournoi/index.html.twig",
                array("tournois" => $tournois, "mestournois" => $mestournois, "jeux" => $jeux));
    }




    /**
     * @Route("/addtournoi", name="addtournoi")
     */
    public function addTournoi(Request $request,SerializerInterface $serializer)
    {
        $tournoi = new Tournoi();
        $user2 = $this->getUser();

        $form = $this->createForm(TournoiType::class, $tournoi);
        $tournoi->setOrganisteur($user2);
        $form->handleRequest($request);
        $type = "ajouter";
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $tournoi->setImage($newFilename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();

            for ($i = 1; $i <= $tournoi->getNbrEquipes(); $i++) {
                $equipe{$i} = new Equipe;
                $equipe{$i}->setLabel("equipe{$i}");
                $equipe{$i}->setTournoi($tournoi);
                $chaine = '';

                for ($j = 1; $j <= $tournoi->getNbrJoueurEq(); $j++) {
                    $chaine.='vide-';
                }

                $equipe{$i}->setJoueurs($chaine);
                $em->persist($equipe{$i});
                $em->flush();

            }
            return $this->redirectToRoute("tournoi");

        }
        return $this->render("tournoi/add-tournoi.html.twig", array("formTournoi" => $form->createView(), "type" => $type));
    }

    /**
     * @Route("/showTournoi{id}",name="showTournoi")
     */
    public function show($id)
    {
        $tournoi = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        return $this->render("tournoi/tournament-details.html.twig", array("tournoi" => $tournoi));

    }


    /**
     * @Route("/tournoiUpdate/{id}",name="tournoiUpdate")
     */
    public function updateTournoi(TournoiRepository $s, $id, Request $request)
    {
        $tournoi = $s->find($id);
        $formTournoi = $this->createForm(TournoiType::class, $tournoi);
        $formTournoi->handleRequest($request);
        $type = "modifier";
        if ($formTournoi->isSubmitted() ) {
            $em = $this->getDoctrine()->getManager();
            $this->getDoctrine()->getManager()->persist($tournoi);
            $em->flush();
            return $this->redirectToRoute("tournoi");
        }
        return $this->render("tournoi/add-tournoi.html.twig", array("formTournoi" => $formTournoi->createView(), "type" => $type, "tournoi" => $tournoi));
    }

    /**
     * @Route("/tournoiInscrit/{id}",name="tournoiInscrit")
     */
    public function inscrireTournoi($id): Response
    {

        if ($this->getUser()) {
            $tournoi = $this->getDoctrine()
                ->getRepository(Tournoi::class)->find($id);
            /*$test=preg_match("vide",$equipe->getJoueurs());*/
            return $this->render("tournoi/tournament-inscription.html.twig",
                array("tournoi" => $tournoi,"current"=>$this->getUser()->getUsername()));
        }
        return $this->redirectToRoute("connexion");
    }

    /**
     * @Route("/equipeInscrit/{id}",name="equipeInscrit")
     */
    public function inscrireEquipe($id): Response
    {

        if ($this->getUser()) {
            $equipe = $this->getDoctrine()
                ->getRepository(Equipe::class)->find($id);
            $tournoi=$equipe->getTournoi();
            $pos = strpos($equipe->getJoueurs(), "vide");
            if ($pos !== false) {
                $chaine = substr_replace($equipe->getJoueurs(), $this->getUser()->getUsername(),$pos,strlen("vide"));
                $equipe->setJoueurs($chaine);
                $this->getDoctrine()->getManager()->flush();
            }

                return $this->render("tournoi/succes-tournament.html.twig",
                    array("equipe" => $equipe ,"current"=>$this->getUser()->getUsername(),"tournoi" => $tournoi));
            }


            return $this->redirectToRoute("connexion");
        }


    /**
     * @Route("/annulInscrit/{id}",name="annulInscrit")
     */
    public function annulInscrit($id,Request $request): Response
    {

        if ($this->getUser()) {
            $equipe = $this->getDoctrine()
                ->getRepository(Equipe::class)->find($id);
            $tournoi=$equipe->getTournoi();
            $pos = strpos($equipe->getJoueurs(), $this->getUser()->getUsername());
            if ($pos !== false) {
                $chaine = substr_replace($equipe->getJoueurs(),"vide" ,$pos,strlen($this->getUser()->getUsername()));
                $equipe->setJoueurs($chaine);
                $this->getDoctrine()->getManager()->flush();
            }

            return $this->redirectToRoute("tournoi");
        }


        return $this->redirectToRoute("connexion");
    }
    /**
     * @Route("/calendar",name="calendar")
     */
    public function calendar(): Response
    {
$events=  $this->getDoctrine()
    ->getRepository(Tournoi::class)->listTournoiByUser($this->getUser()->getUsername());
$tournoi = [];
foreach ($events as $event) {
    $tournoi[] = [
        'id' => $event->getId(),
        'title'=>$event->getNom(),
        'start'=>$event->getTime()->format('Y-m-d H:i:s'),
        'end'=>$event->getTimeEnd()->format('Y-m-d H:i:s')
    ];


}
        $data= json_encode($tournoi);
        return $this->render('tournoi/tournament-calendar.html.twig'
            ,array("data"=>$data));
    }

    /**
     * @Route("/calendar/{id}/edit", name="calendar_tournoi_edit", methods={"PUT"})
     */
    public function majEvent(?Tournoi $tournoi, Request $request)
    {

        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->start) && !empty($donnees->start)

        )

            // On hydrate l'objet avec les données
            $tournoi->setNom($donnees->title);
            $tournoi->setTime(new \DateTime($donnees->start));

            $tournoi->setTimeEnd(new \DateTime($donnees->end));


            $em = $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();

            return new Response('Ok');

    }
    /**
     * @Route("/allTournoiAPI",name="allTournoiAPI", methods={"GET","POST"})
     */
    public function showAll(Request $request): Response{


        $tournoi= $this->getDoctrine()->getRepository(Tournoi::class)->findAll();
        $response = $this->json($tournoi, 200, [], ['groups' => 'post:read']);
        return $response;
    }
//    /**
//     * @Route("/mesTournoiAPI",name="allTournoiAPI", methods={"GET","POST"})
//     */
//    public function showMytournaments(Request $request): Response{
//
//        $mestournois = $this->getDoctrine()
//            ->getRepository(Tournoi::class)->listTournoiByUser($this->getUser()->getUsername());
//
//        $response = $this->json($mestournois, 200, [], ['groups' => 'post:read']);
//        return $response;
//    }


    /**
     * @Route("/addtournoiAPI", name="addtournoiAPI")
     */
    public function addUserJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $tournoi = new Tournoi();
        $em = $this->getDoctrine()->getManager();
        $tournoi->setNom($request->query->get("nom"));
        $tournoi->setNbrEquipes($request->query->get("nbr_equipes"));
        $tournoi->setNbrJoueurEq($request->query->get("nbr_joueur_eq"));
        $tournoi->setPrix($request->query->get("prix"));
        $tournoi->setImage($request->query->get("image"));
        $tournoi->setDiscordChannel($request->query->get("discord_channel"));
        $nomj=$request->query->get("jeu");
        echo "*******" ;
        echo $nomj;
        $jeu =$em->getRepository(Jeu::class)->findOneBy(['nom' => $nomj]);

        $tournoi->setJeu($jeu);
        $em->persist($tournoi);
        $em->flush();
        $date = new \DateTime('@'.strtotime('now'));
       $tournoi->setTime($date);



        $em = $this->getDoctrine()->getManager();
        $em->persist($tournoi);
        $em->flush();

        for ($i = 1; $i <= $tournoi->getNbrEquipes(); $i++) {
            $equipe{$i} = new Equipe;
            $equipe{$i}->setLabel("equipe{$i}");
            $equipe{$i}->setTournoi($tournoi);
            $chaine = '';

            for ($j = 1; $j <= $tournoi->getNbrJoueurEq(); $j++) {
                $chaine.='vide-';
            }

            $equipe{$i}->setJoueurs($chaine);
            $em->persist($equipe{$i});
            $em->flush();

        }
        $jsonContent = $Normalizer->normalize($tournoi, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/updateTournoiAPI/{id}", name="updateTournoiAPI")
     */

    public function updateTournoiAPI(Request $request, NormalizerInterface $Normalizer,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $tournoi = $em->getRepository(Tournoi::class)->find($id);
        $tournoi->setNom($request->get("nom"));
        $tournoi->setPrix($request->get("prix"));
        $tournoi->setDiscordChannel($request->get("discord_channel"));
        $em->flush();
        $jsonContent = $Normalizer->normalize($tournoi, 'json',['groups'=>'post:read']);
        return new Response("Information updated successfully".json_encode($jsonContent));
    }


    /**
     * @Route("/removeTournoiAPI",name="removeTournoi")
     *
     */
    public function deleteAPI(Request $request)
    {
        $id=$request->get("id");
        $tournoi = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tournoi);
        $em->flush();
        return new Response("removed successfully");
    }


    /**
     * @Route("/getTournoiAPI/{id}",name="getTournoi")
     *
     */
    public function getTournoi(Request $request,NormalizerInterface $Normalizer)
    {
        $id=$request->get("id");
        $tournoi = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $jsonContent = $Normalizer->normalize($tournoi, 'json',['groups'=>'post:read']);
        return new Response("Information updated successfully".json_encode($jsonContent));

    }


}
