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
     * @Route (name="home", path="", methods={"GET"})
     */
    public function home (SortieRepository $sortieRepository)
    {
        $sorties = $sortieRepository->findAll ();

        return $this->render('home/home.html.twig',[
            'sorties' => $sorties]);
    }
    /**
     * @Route("/liste_sorties", name="liste_sorties")
     * @param EntityManagerInterface $emi
     * @return Response
     */
    public function index(EntityManagerInterface $emi)
    {
        // LES ETATS
        $etatCree = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'Brouillon']);
        $etatPubliee = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'Publiée']);
        $etatAnnule = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'Annulée']);
        $etatCloture = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'Clôturée']);
        $etatEncours = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'En cours']);
        $etatTerminee = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'Terminée']);
        $etatArchive = $emi->getRepository( Etat::class)->findOneBy(['libelle' => 'Archivée']);

        // TOUTE LES VILLES
        $villes = $emi->getRepository(Ville::class)->findAll();

        // LES REQUETES DE RECUPERATIONS DES SORTIES EN FONCTION DE L'ETAT
        $sortiesPubliees = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatPubliee]);
        $sortiesCrees = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatCree, 'organisateur' => $this->getUser()]);
        $sortiesAnnulees = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatAnnule]);
        $sortiesCloturees = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatCloture]);
        $sortiesEncours = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatEncours]);
        $sortiesTerminees = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatTerminee]);
        $sortiesArchivees = $emi->getRepository(Sortie::class)->findBy(['etat' => $etatArchive]);

        $sortiesPhone = $emi->getRepository(Sortie::class)->findBy(['etat' => [$etatCree, $etatPubliee, $etatCloture, $etatEncours, $etatTerminee], 'site' => $this->getUser()->getSite()]);



        return $this->render('home/home.html.twig', [
            'controller_name' => 'homeController',
            'sortiesPubliees' => $sortiesPubliees,
            'sortiesCrees' => $sortiesCrees,
            'sortiesAnnulees' => $sortiesAnnulees,
            'sortiesCloturees' => $sortiesCloturees,
            'sortiesEncours' => $sortiesEncours,
            'sortiesTerminee' => $sortiesTerminees,
            'sortiesArchivees' => $sortiesArchivees,
            'villes' => $villes,
            'sortiesPhone' => $sortiesPhone
        ]);
    }


}