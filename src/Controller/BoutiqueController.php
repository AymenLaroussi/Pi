<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitsRepository;

class BoutiqueController extends AbstractController
{
    /**
     * @Route("/boutique", name="boutique")
     */
    public function index(ProduitsRepository $repository)
    {
        $produits = $repository->findsearch();
        return $this->render('boutique/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}
