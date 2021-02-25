<?php


namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route (name="home", path="", methods={"GET"})
     */
    public function home (SortieRepository $sortieRepository)
    {
        $sorties = $sortieRepository->findAll ();

        return $this->render('home/home.html.twig',[
            'sorties' => $sorties]);
    }

}