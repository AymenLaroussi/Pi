<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentairesType;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
    public function show($id,Request $request): Response{
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $produits= $this->getDoctrine()->getRepository(Produits::class)->findAll();
        $user=$this->getUser();
        $produitf= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $comment = new Commentaires();
        $comment->setDate(new \DateTime('now'));
        $comment->setUser($user);
        $comment->setProduit($produitf);
        
        $form = $this->createForm(CommentairesType::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show',array('id' => $id), Response::HTTP_SEE_OTHER);
        }

        

        return $this->render("boutique/detail.html.twig",array("produit"=>$produit ,"produits"=>$produits,'form1' => $form->createView(), ));
    }
}
