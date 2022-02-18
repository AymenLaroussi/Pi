<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     */
     public function index(AuthenticationUtils $authenticationUtils): Response {
         $utilisateur = $this->getUser();
         if($utilisateur)
         {
             return $this->redirectToRoute('home');
         }

         $error = $authenticationUtils->getLastAuthenticationError();

         $lastUsername = $authenticationUtils->getLastUsername();

         return $this->render('connexion/connexion.html.twig', [
                          'controller_name' => 'LoginController',
                          'last_username' => $lastUsername,
                          'error'         => $error,
          ]);
      }
}
