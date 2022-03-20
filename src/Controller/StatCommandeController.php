<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatCommandeController extends AbstractController
{/**
 * @Route("/StatCommande", name="StatCommande")
 */
    public function StatCommande(): Response
    {

        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $quantitydemande= [];
        $id_produit = [];
        foreach ($commandes as $commande) {

            $id_produit [] = $commande->getProduits();
        }
        return $this->render('stat_commande/statcom.html.twig', [
            'quantitydemande' => json_encode($quantitydemande),
            'id_produit' => json_encode($id_produit)
        ]);
    }
}
