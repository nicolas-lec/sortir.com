<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SortieOrganisateurController extends AbstractController
{
    /**
     * @Route("/sortie/organisateur", name="sortie_organisateur",methods={"GET", "POST"})
     */
    public function index(SortieRepository $repository, EntityManagerInterface $em)
    {

        $etat = $em->getRepository('App:Etat')->find(2);
        $user = $this->getUser();
        $sorties = $em->getRepository(Sortie::class)->getByIdOrganisateur($user,$etat);
        return $this->render('sortie_organisateur/index.html.twig',[
            'sorties' => $sorties]);
    }
}
