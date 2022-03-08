<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CommandeRepository;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(CommandeRepository $cmdrepo): Response
    {
        $cmds=$cmdrepo->findAll();
        return $this->render('commande/Affichback.html.twig',[
            'cmds'=>$cmds
        ]);
    }
}
