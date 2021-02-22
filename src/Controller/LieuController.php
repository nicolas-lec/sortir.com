<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/controlleur", name="lieu_controlleur")
     */
    public function index(): Response
    {
        return $this->render('lieu_controlleur/sortie.html.twig', [
            'controller_name' => 'LieuController',
        ]);
    }
}
