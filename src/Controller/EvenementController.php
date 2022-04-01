<?php

namespace App\Controller;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Evenement;
use App\Form\EvenetType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\UploadedFile;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


/**
 * @Route("admin/evenements")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="list_evenements", methods={"GET", "POST"})
     */
    public function index(Request $request, EvenementRepository $evenementsRepository): Response
    {   
        $keyWord = $request->query->get('keyWord', null);
        
        /** name mta3 l search buttonn lezm tsamih keyWord */
          $keyWord = $request->request->get('keyWord', null); 
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementsRepository->getAll($keyWord),
           
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
    public function delete($id, MailerInterface $mailer){
        $event= $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $user= $this->getDoctrine()->getRepository(User::class)->findAll(); 
        $em= $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
        foreach($user as $utilisateur)
        {
        $email = (new Email()) 
                ->from('aicha.salhi@esprit.tn')
                ->to($utilisateur->getEmail())
                ->subject('A propos un evenement')
                ->text('Bonjour , Nous voulons , l administration de l equipe Azerty Crew , vous informé qu un evenement a ete supprimer et non plus disponible.  Cordialement Admin. ');

                $mailer->send($email);
            }

        return $this->redirectToRoute("list_evenements");
    }
}
