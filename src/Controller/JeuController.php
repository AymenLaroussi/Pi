<?php

namespace App\Controller;
use App\Form\JeuType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Jeu;
use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;

/**
 * @Route("admin/jeu")
 */
class JeuController extends AbstractController
{
    /**
     * @Route("/", name="list_jeu", methods={"GET"})
     */
    public function index(JeuRepository $jeuRepository): Response
    {
        return $this->render('jeu/index.html.twig', [
            'jeux' => $jeuRepository->findAll(),
        ]);
    }
    /**
     * @Route("/ajout", name="ajout_jeu", methods={"GET","POST"})
     */
    public function ajout(Request $request): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
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
            $jeu->setImage($newFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeu);
            $entityManager->flush();

            return $this->redirectToRoute('list_jeu', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeu/ajout.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="voir_jeu", methods={"GET"})
     */
    public function voir(Jeu $jeu): Response
    {
        return $this->render('jeu/voir.html.twig', [
            'jeu' => $jeu,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier_jeu", methods={"GET","POST"})
     */
    public function modifier(Request $request, Jeu $jeu): Response
    {
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_jeu');
        }

        return $this->render('jeu/modifier.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/supprimer/{id}", name="supprimer_jeu")
     */
    public function delete($id){
        $em= $this->getDoctrine()->getManager();
        $jeu=$em->getRepository(Jeu::class)->find($id);

        $em->remove($jeu);
        $em->flush();
        return $this->redirectToRoute("list_jeu");
    }


}
