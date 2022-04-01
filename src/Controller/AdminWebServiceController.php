<?php

namespace App\Controller;

use DateTime;
use phpDocumentor\Reflection\DocBlock\Serializer;
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

use App\Repository\UserRepository;


    /**
     * @Route("/web/service/admin", name="app_web_service")
     */
class AdminWebServiceController extends AbstractController
{

    /**
     * @Route("/web/service", name="app_web_service")
     */
    public function index(): Response
    {
        return $this->render('web_service/index.html.twig', [
            'controller_name' => 'AdminWebServiceController',
        ]);
    }

    # R  Produits #
    /**
    * @Route("/produits", name="produits" , methods={"GET"})
    */
    public function getProduits( SerializerInterface $SerializerInterface,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, NormalizerInterface $normalizer)
    {
        $produits=$produitsRepository->findAll();
        $categorie=$categoriesRepository->findAll();
        $response = $this->json([$produits, $categorie], 200, [], ['groups' => 'post:read']);
        return $response;
    }

    # D Commentaires #
      /**
      * @Route("/supprimer", name="supprimer")
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


         # C Categiorie #
         /**
         * @Route("/ajoutC", name="ajout_cat")
         * @Method("POST")
         */
        public function AjoutCategorie (Request $request)
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
}
