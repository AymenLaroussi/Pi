<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commandes;
use App\Repository\CommandesRepository;

/**
 * @Route("admin/commandes")
 */
class CommandesController extends AbstractController
{
    /**
     * @Route("/", name="liste_commandes", methods={"GET"})
     */
    public function index(CommandesRepository $commande): Response
    {
        return $this->render('commandes/index.html.twig', [
            'commandes' => $commande->findAll(),
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer_commandes")
     */
    public function delete($id)
    {
        $category = $this->getDoctrine()->getRepository(Commandes::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute("liste_commandes");
    }
}