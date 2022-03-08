<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Entity\Rating;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentairesType;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use App\Repository\EvenementRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ProduitsRepository $produitsRepository,EvenementRepository $evenementsRepository): Response
    {
        $produit =$produitsRepository->findAll();
        $evenements =$evenementsRepository->findAll();
        return $this->render('home/index.html.twig',array
        ("evenements"=> $evenements,"produits"=> $produit));
    }

    /**
     * @Route("/boutique/{id}",name="show", methods={"GET","POST"})
     */
    public function show($id,Request $request): Response{
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $produit= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $produits= $this->getDoctrine()->getRepository(Produits::class)->findAll();
        $ratis= $this->getDoctrine()->getRepository(Rating::class)->findAll();
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



        return $this->render("boutique/detail.html.twig",array("produit"=>$produit ,"produits"=>$produits,"ratis"=>$ratis,'form1' => $form->createView(), ));
    }



    /**
     * @Route("/boutique/api/{id}",name="show1", methods={"GET","POST"})
     */
    public function show1(SerializerInterface $SerializerInterface, $id,Request $request, ValidatorInterface $validator): Response{

        $user=$this->getUser();
        $produitf= $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $comment = new Commentaires();

        $requete = $request->getContent();
        try {
            $post = $serializer->deserialize($requete, Commentaires::class, 'json');
            $comment->setDate(new \DateTime('now'));
            $comment->setUser($user);
            $comment->setProduit($produitf);
            $comment->setCreatedAt(new \DateTime());
            $errors = $validator->validate($comment);
            if (count($errors) > 8) {
                return $this->json($errors, 400);
            }
            $em->persist($comment);
            $em->flush();
            return $this->json($comment, 201, [], ['groups' => 'post:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
