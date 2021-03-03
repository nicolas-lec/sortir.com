<?php

namespace App\Controller;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site/controlleur", name="site_controlleur")
     */
    public function index(EntityManagerInterface $emi, Request $request): Response
    {
        $site = $emi->getRepository(Site::class)->findAll($this->getId());
        return $this->render('site_controlleur/sortie.html.twig', [
            'SiteController' => 'SiteController', 'site'=>$site
        ]);
    }
}
