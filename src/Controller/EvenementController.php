<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenetType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\UploadedFile;


/**
 * @Route("admin/evenements")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="list_evenements", methods={"GET"})
     */
    public function index(EvenementRepository $evenementsRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementsRepository->findAll(),
           
        ]);
    }

    /**
     * @Route("/ajout", name="ajout_evenements", methods={"GET","POST"})
     */
    public function ajout(Request $request): Response
    {
        $event = new Evenement();
        $form = $this->createForm(EvenetType::class, $event);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
                  /** @var UploadedFile $uploadedFile */
                  $uploadedFile = $form['imageFile']->getData();
                  $destination = $this->getParameter('kernel.project_dir').'/public/uploads/event';
                  $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                  $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                  $uploadedFile->move(
                      $destination,
                      $newFilename
                  );
                  $event->setImage($newFilename);
      
      
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('list_evenements', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/ajout.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voir_evenements", methods={"GET"})
     */
    public function voir(int $id): Response
    {
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find((int)$id);
        return $this->render('evenement/voir.html.twig', [
            'event' => $event,
            'sponsors' => $event->getSponsors()
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier_evenements", methods={"GET","POST"})
     */
    public function modifier(Request $request, Evenement $event): Response
    {
        $form = $this->createForm(EvenetType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_evenements');
        }

        return $this->render('evenement/modifier.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

   

    /**
     * @Route("/supprimer/{id}", name="supprimer_evenements")
     */
    public function delete($id){
        $event= $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute("list_evenements");
    }
}
