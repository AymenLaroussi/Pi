<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use App\Entity\Produits;

/**
 * @Route("boutique")
 */
class BoutiqueController extends AbstractController
{

    
    /**
     * @Route("/", name="boutique")
     */
    public function index(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('boutique/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

      /**
     * @Route("/{id}",name="show", methods={"GET"})
     */
    public function show($id){
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $produits= $this->getDoctrine()->getRepository(Produits::class)->findAll();
        

        return $this->render("boutique/detail.html.twig",array("produit"=>$produit ,"produits"=>$produits ));
    }
}
