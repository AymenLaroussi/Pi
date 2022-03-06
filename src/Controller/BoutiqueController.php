<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use App\Entity\Produits;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentairesType;
use App\Entity\User;
use App\Entity\Rating;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


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
     * @Route("/{id}",name="show" )
     */
    public function show($id,CommentairesRepository $commentairesRepository, ProduitsRepository $produitsRepository,Request $request): Response{
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

        

        return $this->render("boutique/detail.html.twig",[
            "produit"=>$produit,
            "produits"=>$produits,
            'comment' => $comment,
            'form1' => $form->createView(),
        ]);  
    }
    
     /**
     * @Route("/categorie/{id}", name="listeproduits")
     */
    public function listProduitsByCategories(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository,$id)
    {
        $produits=$produitsRepository->listProduitsByCategories($id);
        return $this->render("boutique/index.html.twig",[
            'produits' => $produits,
            'categories' => $categoriesRepository->findAll(),
        ]);
    }
     
     /**
     * @Route("/ajout/{id}", name="ajout_commentaire", methods={"GET","POST"}))
     */
    public function ajout(Request $request, $id): Response
    {
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

        return $this->render('boutique/ajout.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }


    /**
    * @Route("/addrating ", name="addrating")
    */
    public function searchStudentx(Request $request)
    {
        $ref=$request->get('ref');
        $rat=$request->get('rating');
        $user=$request->get('rating');
        $rating =new Rating($ref,$rat,$user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rating);
        $entityManager->flush();
    }


     ////////////////////JASON/////////////////
    
    /**
    * @Route("/api/", name="boutiqueJSON" , methods={"GET"})
    */
    public function getProduits( SerializerInterface $SerializerInterface,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, NormalizerInterface $normalizer)
    {
        $produits=$produitsRepository->findAll(); 
        $response = $this->json($produits, 200, [], ['groups' => 'post:read']);
        return $response;
    }
    



}
