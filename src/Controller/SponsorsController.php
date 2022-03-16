<?php

namespace App\Controller;

use App\Entity\Sponsors;
use App\Form\SponsorsType;
use App\Repository\SponsorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\UploadedFile;

/**
 * @Route("admin/sponsors")
 */
class SponsorsController extends AbstractController
{
    /**
     * @Route("/", name="list_sponsors", methods={"GET"})
     */
    public function index(SponsorsRepository $sponsorsRepository): Response
    {
        return $this->render('sponsors/index.html.twig', [
            'sponsors' => $sponsorsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="ajout_sponsors", methods={"GET","POST"})
     */
    public function ajout(Request $request): Response
    {
        $spons = new Sponsors();
        $form = $this->createForm(SponsorsType::class, $spons);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads/sponsor';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $spons->setImage($newFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($spons);
            $entityManager->flush();

            return $this->redirectToRoute('list_sponsors', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sponsors/ajout.html.twig', [
            'spons' => $spons,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voir_sponsors", methods={"GET"})
     */
    public function voir(Sponsors $spons): Response
    {
        return $this->render('sponsors/voir.html.twig', [
            'spons' => $spons,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier_sponsors", methods={"GET","POST"})
     */
    public function modifier(Request $request, Sponsors $spons): Response
    {
        $form = $this->createForm(SponsorsType::class, $spons);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_sponsors');
        }

        return $this->render('sponsors/modifier.html.twig', [
            'spons' => $spons,
            'form' => $form->createView(),
        ]);
    }

   

    /**
     * @Route("/supprimer/{id}", name="supprimer_sponsors")
     */
    public function delete($id){
        $spons= $this->getDoctrine()->getRepository(Sponsors::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($spons);
        $em->flush();
        return $this->redirectToRoute("list_sponsors");
    }
}
