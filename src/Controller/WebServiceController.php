<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Repository\CategoriesRepository;
use App\Entity\Produits;
use App\Entity\Commentaires;
use App\Entity\Categories;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentairesType;
use App\Entity\User;
use App\Entity\Rating;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Doctrine\ORM\Query;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Doctrine\ORM\QueryBuilder;
use App\Form\RechercheProduitType;
use App\Repository\RatingRepository;
use Symfony\Component\Serializer\Serializer;
use App\Repository\UserRepository;


/**
     * @Route("/web/service", name="app_web_service")
     */
class WebServiceController extends AbstractController
{

    /**
     * @Route("/web/service", name="app_web_service")
     */
    public function index(): Response
    {
        return $this->render('web_service/index.html.twig', [
            'controller_name' => 'WebServiceController',
        ]);
    }

    

    # RETRIVE  Produits #
    /**
    * @Route("/produits", name="produits")
    * @Method("GET")
    */
    public function getProduits( SerializerInterface $SerializerInterface,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, NormalizerInterface $normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Produits::class);
        $produits = $repository->findAll();
        

        $jsonContent = $normalizer->normalize($produits, 'json',['groups'=>'post:read']);
        
        return new Response(json_encode($jsonContent));
    }

    # RETRIVE {id} Produit détaillé #
    /**
     * @Route("/produit",name="produit")
     * @Method("GET")
     */
    public function show(Request $request, SerializerInterface $SerializerInterface,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, NormalizerInterface $normalizer): Response{
        
        $id = $request->get("id");
        $repository= $this->getDoctrine()->getRepository(Produits::class);
        $produits = $repository->find($id);
        $jsonContent = $normalizer->normalize($produits, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }


    # DELETE Commentaires #
      /**
      * @Route("/commentaire/supprimer", name="supprimer_commentaire")
      * @Method("DELETE")
      */
    public function supprimer(Request $request){
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository(Commentaires::class)->find($id);
        if($commentaire!=null ){
            $em->remove($commentaire);
            $em->flush();

            return new JsonResponse("Commentaire supprimer avec succes");

        }
        return new JsonResponse("id reclamtion non valide");
    }

    # DELETE Produit #
      /**
      * @Route("/produit/supprimer", name="supprimer_produit")
      * @Method("DELETE")
      */
    public function supprimerProduit(Request $request){
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produits::class)->find($id);
        if($produit!=null ){
            $em->remove($produit);
            $em->flush();

            return new JsonResponse("Produit supprimer avec succes");

        }
        return new JsonResponse("id reclamtion non valide");
    }


    # DELETE Categorie #
      /**
      * @Route("/categorie/supprimer", name="supprimer_categorie")
      * @Method("DELETE")
      */
    public function supprimerCategorie(Request $request){
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categories::class)->find($id);
        if($categorie!=null ){
            $em->remove($categorie);
            $em->flush();

            return new JsonResponse("Produit supprimer avec succes");

        }
        return new JsonResponse("id reclamtion non valide");
    }



         # C Commentaire #
         /**
         * @Route("/commentaires/ajout", name="ajout_commentaire")
         */
        public function AjoutCommentaire (Request $request,NormalizerInterface $Normalizer)
        {
            $id = "1";
            $user1="7";
            $produitf= $this->getDoctrine()->getRepository(Produits::class)->find($id);
            $user= $this->getDoctrine()->getRepository(User::class)->find($user1);
            $commentaire = new Commentaires();
            
            $message = $request->query->get("message");
           
            $em = $this->getDoctrine()->getManager();
            $commentaire->setMessage($message);
            $commentaire->setDate(new DateTime('now'));
            $commentaire->setUser($user);
            $commentaire->setProduit($produitf);

            $em->persist($commentaire);
            $em->flush();
            $jsonContent = $Normalizer->normalize($commentaire, 'json', ['groups'=>'post:commentaire']);
            return new Response(json_encode($jsonContent));
        }

        # C Commentaire #
         /**
         * @Route("/ajoutC", name="ajout_cat")
         * @Method("POST")
         */
        public function AjoutCategories (Request $request)
        {
            
            $categorie = new Categories();
            
            $nom = $request->query->get("nom");
            $em = $this->getDoctrine()->getManager();
            
            
            $categorie->setNom($nom);
            $em->persist($categorie);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($categorie);
            return new JsonResponse ($formatted);
        }




        /**
        * @Route("/categorie/ajout", name="ajout_categorie")
        */

        public function AjoutCategorie(Request $request, NormalizerInterface $Normalizer){

            $em = $this->getDoctrine()->getManager();
            $categorie = new Categories();
            $categorie->setNom($request->get('nom'));
            

            $em->persist($categorie);
            $em->flush();
            $jsonContent = $Normalizer->normalize($categorie, 'json', ['groups'=>'cat']);
            return new Response(json_encode($jsonContent));
        
        }

        /**
        * @Route("/produit/ajout", name="ajout_produit")
        */

        public function AjoutPrdouit(Request $request, NormalizerInterface $Normalizer){

            $produit = new Produits();
            
            $titre = $request->query->get("titre");
            $description = $request->query->get("description");
            $promo = $request->query->get("promo");
            $stock = $request->query->get("stock");
            $ref = $request->query->get("ref");
            $longdescription = $request->query->get("longdescription");
            $prix = $request->query->get("prix");
            $em = $this->getDoctrine()->getManager();
            $produit->setTitre($titre);
            $produit->setDescription($description);
            $produit->setPromo($promo);
            $produit->setStock($stock);
            $produit->setFlash("0");
            $produit->setImage("test.jpg");
            $produit->setRef($ref);
            $produit->setLongdescription($longdescription);
            $produit->setprix(floatval($prix));
            $id = $request->get("categorie");
            $categorie= $this->getDoctrine()->getRepository(Categories::class)->find($id);
            //127.0.0.1:8000/web/service/produits/ajout?titre=ka&description=test&promo=1&stock=10&flash=0&ref=10&longdescription=test&prix=1000.0&categories=3
            
            $produit->setCategories($categorie);

            $em->persist($produit);
            $em->flush();
            $jsonContent = $Normalizer->normalize($produit, 'json', ['groups'=>'read','cat']);
            return new Response(json_encode($jsonContent));
        
        }
        
        

        # Retrive liste des catégories #
        /**
        * @Route("/categories", name="categories" )
        * @Method("GET")
        */
        public function getCategories( SerializerInterface $SerializerInterface,CommentairesRepository $commentaireRepository, NormalizerInterface $normalizer)
        {
            $repository = $this->getDoctrine()->getRepository(Categories::class);
            $categorie = $repository->findAll();
            

            $jsonContent = $normalizer->normalize($categorie, 'json',['groups'=>'cat']);
            
            return new Response(json_encode($jsonContent));
        }

        # Retrive commentaire des produits
        /**
         * @Route("/commentaireid", name="commentaireID")
         */
        public function getspecifiedid(Request $request ,SerializerInterface $SerializerInterface,CommentairesRepository $commentaireRepository, NormalizerInterface $normalizer)
        {
            $id = $request->get("id");
            $coment= $this->getDoctrine()->getRepository(Commentaires::class)->listCommentaireByProduit($id);
            $jsonContent = $normalizer->normalize($coment,  'json',['groups'=>'post:commentaire']);
            
            return new Response(json_encode($jsonContent));

        }

        # Retrive  affichage commentaire #
        /**
        * @Route("/commentaires", name="commenatires" )
        * @Method("GET")
        */
        public function getCommentaires1( SerializerInterface $SerializerInterface,CommentairesRepository $commentaireRepository, NormalizerInterface $normalizer)
        {
            $coment= $this->getDoctrine()->getRepository(Commentaires::class)->findAll();
            $jsonContent = $normalizer->normalize($coment,  'json',['groups'=>'post:commentaire']);
            
            return new Response(json_encode($jsonContent));
        }



        

}
