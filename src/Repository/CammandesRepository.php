<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Recomme;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/commandes")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="list_commande", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Recomme
    {
        return $this->render('commande/index.html.twig', [
            'commande' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="ajout_commande", methods={"GET","POST"})
     */
    public function ajout(Request $request): Recomme
    {
        $comm = new Commande();
        $form = $this->createForm(CommandeType::class, $comm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comm);
            $entityManager->flush();

            return $this->redirectToRoute('list_commande', [], Recomme::HTTP_SEE_OTHER);
        }

        return $this->render('commande/ajout.html.twig', [
            'comm' => $comm,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voir_commande", methods={"GET"})
     */
    public function voir(Commande $comm): Recomme
    {
        return $this->render('commande/voir.html.twig', [
            'comm' => $comm,
        ]);
    }

       

    /**
     * @Route("/supprimer/{id}", name="supprimer_commande")
     */
    public function delete($id){
        $comm= $this->getDoctrine()->getRepository(Commande::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($comm);
        $em->flush();
        return $this->redirectToRoute("list_commande");
    }
}
