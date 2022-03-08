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
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use App\Form\RechercheProduitType;
use App\Repository\RatingRepository;

use App\Repository\UserRepository;


/**
 * @Route("boutique")
 */
class BoutiqueController extends AbstractController
{

    
    /**
     * @Route("/", name="boutique")
     */
    public function index(RatingRepository $ratingRepository,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, Request $request, PaginatorInterface $paginator ): Response
    {   
        $user= $this->getDoctrine()->getRepository(User::class)->findAll();
        $ratis= $this->getDoctrine()->getRepository(Rating::class)->findAll();
        $haut= $this->getDoctrine()->getRepository(Produits::class)->orderByPrixHaut();
        $bas= $this->getDoctrine()->getRepository(Produits::class)->orderByPrixBas();
         $formSearch= $this->createForm(RechercheProduitType::class, null, [
            'attr' => [
                'class' => 'search-form'
            ]
            ]);
         $formSearch->handleRequest($request);
         if($formSearch->isSubmitted()){
             $titre= $formSearch->getData()->getTitre();
             $result= $this->getDoctrine()->getRepository(Produits::class)->RechercheProduit($titre);
             $produits = $paginator->paginate(
                $result,
                $request->query->getInt('page', 1),
            );
             return $this->render("boutique/index.html.twig",array(
             "produits"=>$produits,
             "haut"=>$haut,
             "bas"=>$bas,
             'categories' => $categoriesRepository->findAll(),
             "ratis"=>$ratis,
             'formSearch'=>$formSearch->createView()
             
             
            ));
         }
        $donnees= $this->getDoctrine()->getRepository(Produits::class)->findAll();
        $produits = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('boutique/index.html.twig', [
            "ratis"=>$ratis,
            'produits' => $produits,
            'categories' => $categoriesRepository->findAll(),
            'formSearch'=>$formSearch->createView(),
            "haut"=>$haut,
             "bas"=>$bas,
             "ratis"=>$ratis,
             "user"=>$user,
            
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
    public function listProduitsByCategories(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository,$id, PaginatorInterface $paginator,Request $request ): Response
    {
        $donnees= $produitsRepository->listProduitsByCategories($id);
        $produits = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        
       
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
        $user=$request->get('user');
        $rating =new Rating($ref,$rat,$user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rating);
        $entityManager->flush();
    }


     /**
     * @Route("/ASC", name="ASC")
     */
    public function ASC(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, Request $request, PaginatorInterface $paginator ): Response
    {   
        
         $formSearch= $this->createForm(RechercheProduitType::class, null, [
            'attr' => [
                'class' => 'search-form'
            ]
            ]);
         $formSearch->handleRequest($request);
         if($formSearch->isSubmitted()){
             $titre= $formSearch->getData()->getTitre();
             $result= $this->getDoctrine()->getRepository(Produits::class)->RechercheProduit($titre);
             $produits = $paginator->paginate(
                $result,
                $request->query->getInt('page', 1),
            );
             return $this->render("boutique/asc.html.twig",array(
             "produits"=>$produits,
            
             'categories' => $categoriesRepository->findAll(),
             'formSearch'=>$formSearch->createView()
             
            ));
         }
        $donnees= $this->getDoctrine()->getRepository(Produits::class)->orderByPrixHaut();
        $produits = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('boutique/asc.html.twig', [
            'produits' => $produits,
            'categories' => $categoriesRepository->findAll(),
            'formSearch'=>$formSearch->createView(),
            
            
        ]);
    } 

     /**
     * @Route("/DESC", name="DESC")
     */
    public function DESC(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, Request $request, PaginatorInterface $paginator ): Response
    {   
        
        $bas= $this->getDoctrine()->getRepository(Produits::class)->orderByPrixBas();
         $formSearch= $this->createForm(RechercheProduitType::class, null, [
            'attr' => [
                'class' => 'search-form'
            ]
            ]);
         $formSearch->handleRequest($request);
         if($formSearch->isSubmitted()){
             $titre= $formSearch->getData()->getTitre();
             $result= $this->getDoctrine()->getRepository(Produits::class)->RechercheProduit($titre);
             $produits = $paginator->paginate(
                $result,
                $request->query->getInt('page', 1),
            );
             return $this->render("boutique/desc.html.twig",array(
             "produits"=>$produits,
             'categories' => $categoriesRepository->findAll(),
             'formSearch'=>$formSearch->createView()
             
            ));
         }
        $donnees= $this->getDoctrine()->getRepository(Produits::class)->orderByPrixBas();
        $produits = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('boutique/desc.html.twig', [
            'produits' => $produits,
            'categories' => $categoriesRepository->findAll(),
            'formSearch'=>$formSearch->createView(),
            
            
        ]);
    } 

          /**
     * @Route("/FLASH", name="FLASH")
     */
    public function FLASH(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, Request $request, PaginatorInterface $paginator ): Response
    {   
        $bas= $this->getDoctrine()->getRepository(Produits::class)->orderByPrixBas();
         $formSearch= $this->createForm(RechercheProduitType::class, null, [
            'attr' => [
                'class' => 'search-form'
            ]
            ]);
         $formSearch->handleRequest($request);
         if($formSearch->isSubmitted()){
             $titre= $formSearch->getData()->getTitre();
             $result= $this->getDoctrine()->getRepository(Produits::class)->RechercheProduit($titre);
             $produits = $paginator->paginate(
                $result,
                $request->query->getInt('page', 1),
            );
             return $this->render("boutique/flash.html.twig",array(
             "produits"=>$produits,
             'categories' => $categoriesRepository->findAll(),
             'formSearch'=>$formSearch->createView()
             
            ));
         }
        $donnees= $this->getDoctrine()->getRepository(Produits::class)->orderByFlash();
        $produits = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('boutique/flash.html.twig', [
            'produits' => $produits,
            'categories' => $categoriesRepository->findAll(),
            'formSearch'=>$formSearch->createView(),
            
        ]);
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
