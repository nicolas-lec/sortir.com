<?php


namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route (name="home", path="", methods={"GET", "POST"})
     */
    public function home (SortieRepository $sortieRepository)
    {
        $sorties = $sortieRepository->findAll ();

        return $this->render('home/home.html.twig',[
            'sorties' => $sorties]);
    }



}