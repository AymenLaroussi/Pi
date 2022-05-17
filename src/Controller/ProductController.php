<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_index")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #***************************MOBILE*******************************#

    /**
     * @Route("/afficherproduitjson", name="afifcher_json", methods={"GET"})
     */
    public function afficherJSON(ProductRepository $rep, SerializerInterface $serializer): Response
    {
        $result = $rep->findAll();
        $json = $serializer->serialize($result, 'json', ['groups' => 'produit:read']);
        return new JsonResponse($json, 200, [], true);
    }
}
