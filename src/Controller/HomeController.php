<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

     /**
     * @Route("/boutique/{id}",name="show", methods={"GET"})
     */
    public function show($id){
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $produits= $this->getDoctrine()->getRepository(Produits::class)->findAll();
        

        return $this->render("boutique/detail.html.twig",array("produit"=>$produit ,"produits"=>$produits ));
    }
}
