<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Entity\Caegories;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\CategoriesRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("admin/produits")
 */
class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="list_produits", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
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


    /**
     * @Route("/produits/imprimer", name="listProduits", methods={"GET"})
     */
    public function listProduitsPDF(ProduitsRepository $rep ) :Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->set('defaultFont', 'Courier New');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $reader=$rep->findAll();


        // Retrieve the HTML generated in our twig file

         $html = $this->renderView('produits/imprimer.html.twig', array(
            'reader'=>$reader
        ));

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Listesproduits.pdf", [
            "Attachment" => false
        ]);

        // Send some text response
        return new Response("The List of Products has been succesfully generated as a PDFfile  !");

    }


   
}
