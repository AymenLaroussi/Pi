<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $utilisateur = $this->getUser();
        if($utilisateur)
        {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('connexion/connexion.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/deconnexion", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new \Exception('err');
    }
}