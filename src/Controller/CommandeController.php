<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CommandeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(CommandeRepository $cmdrepo): Response
    {
        $cmds=$cmdrepo->findAll();
        return $this->render('commande/Affichback.html.twig',[
            'cmds'=>$cmds
        ]);
    }
    /**
     * @Route("/commande", name="commande")
     */
    public function indexAPI(CommandeRepository $cmdrepo): Response
    {
        $cmds=$cmdrepo->findAll();
        return $this->render('commande/Affichback.html.twig',[
            'cmds'=>$cmds
        ]);
    }
    /**
     * @Route("/addCommande", name="jsonadd")
     *
     */
    public function ajouterjson(Request $request)
    {
        $commande = new Commande();


        $montant = $request->query->get("montant");


        $commande->setTotal($montant);


        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);
        return new JsonResponse($formatted);

    }

    /******************affichage Commande*****************************************/

    /**
     * @Route("/displayCommande", name="display_Commande")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function allRecAction()
    {

        $commande = $this->getDoctrine()->getManager()->getRepository(Commande::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);

        return new JsonResponse($formatted);



    }



}
