<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Users;

class InscriptionController extends Controller
{
    /**
     * @Route("/inscription", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $utilisateur=$this->getUser();
        if($utilisateur) {
            return $this->redirectToRoute('home');
        }

        $users = new Users();
        $form = $this->createForm(UserType::class, $users);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($users, $users->getPlainPassword());
            $users->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($users);
            $entityManager->flush();

            return $this->redirectToRoute('user_registration');
        }

        return $this->render(
            'inscription/inscription.html.twig',
            array('form' => $form->createView())
        );
    }
}