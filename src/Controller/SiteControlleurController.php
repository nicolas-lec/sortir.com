<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteControlleurController extends AbstractController
{
    /**
     * @Route("/site/controlleur", name="site_controlleur")
     */
    public function index(): Response
    {
        return $this->render('site_controlleur/index.html.twig', [
            'controller_name' => 'SiteControlleurController',
        ]);
    }
}
