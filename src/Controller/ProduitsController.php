<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;

/**
 * @Route("admin/produits")
 */
class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="list_produits", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="ajout_produits", methods={"GET","POST"})
     */
    public function ajout(Request $request): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $produit->setImage($newFilename);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('list_produits', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/ajout.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voir_produits", methods={"GET"})
     */
    public function voir(Produits $produit): Response
    {
        return $this->render('produits/voir.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier_produits", methods={"GET","POST"})
     */
    public function modifier(Request $request, Produits $produit): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_produits');
        }

        return $this->render('produits/modifier.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

   

    /**
     * @Route("/supprimer/{id}", name="supprimer_produits")
     */
    public function delete($id){
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute("list_produits");
    }
}
