<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserCtrlController extends AbstractController
{
    /**
     * @Route("/allUsers", name="allUsers")
     */
    public function allUsers(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        $jsonContent = $Normalizer->normalize($users, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addUser/new", name="addUser")
     */
    public function addUserJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $date = new \DateTime('@'.strtotime('now'));
        $user->setDateCreation($date);
        $em->persist($user);
        $em->flush();
        $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/verifierUserJSON", name="verifierUserjson")
     */
    public function verifierUserJSON(Request $request, UserRepository $repo, NormalizerInterface $Normalizer)
    {
        $user = $repo->findByUsername($request->get('username'));
        if($user) {
            if($user[0]->getPassword()==$request->get('password')) {
                return New Response("OK");
            } else {
                return New Response("Wrong");
            }
        } else {
            return New Response("No");
        }
    }

    /**
     * @Route("/updateUser/{usernamex}", name="updateUser")
     */
    public function updateUserAPI(Request $request, NormalizerInterface $Normalizer,$usernamex)
    {
        $em = $this->getDoctrine()->getManager();
        $tournoi = $em->getRepository(User::class)->find($usernamex);
        $tournoi->setUsername($request->get("username"));
        $tournoi->setPassword($request->get("password"));
        $em->flush();
        $jsonContent = $Normalizer->normalize($tournoi, 'json',['groups'=>'post:read']);
        return new Response("Information updated successfully".json_encode($jsonContent));
    }


}
