<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route (name="sortie_", path="sortie/")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function index(): Response
    {
        return $this->render('sortie/sortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }


    /**
     * @Route(name="creationSortie", path="creation-sortie", methods={"GET","POST"})
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function createSortie(Request $request, EntityManagerInterface $entityManager)
    {
        // Initialiser l'objet mappé au formulaire
        $sortie = new Sortie();

        $sortie->setDateHeureDebut(new \DateTime('now')) ;

        // Création du formulaire
        $form = $this->createForm(SortieType::class, $sortie);

        // Récupération des données de la requête HTTP (Navigateur) au formulaire
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Insertion de l'objet en BDD
            $entityManager->persist($sortie);

            // Validation de la transaction
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Votre sortie a été ajoutée avec succès !');
        }
        return $this->render('sortie/sortie.html.twig', ['formSortie' => $form->createView()]);

    }


    /**
     * @Route(name="sortie", path="", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function sortie(Request $request, EntityManagerInterface $entityManager)
    {
        // Création du formulaire
        $formSortie = $this->createForm('App\Form\SortieType');

        // Récupération des données de la requête HTTP (Navigateur) au formulaire
        $formSortie->handleRequest($request);

        // Vérification de la soumission du formulaire
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {
            $category = $formSortie->get('category')->getData();
        }

        // Récupération des idées dans la base de données
        $sorties = $entityManager->getRepository('App:Sortie')->getAll($category ?? null);

        return $this->render('sortie/sortie.html.twig', ['sorties' => $sorties, 'formSortie' => $formSortie->createView()]);
    }


    /**
     * @Route(name="detailSortie", methods={"GET","POST"}, path="detail/{id}", requirements={"id": "\d+"})
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function detailSortie($id, SortieRepository $repository)
    {
        $sortie =$repository->find($id);

        return $this->render('sortie/detailSortie.html.twig', ['sortie'=>$sortie]);
    }


    /**
     * @Route(name="inscriptionSortie",path="inscriptionSortie/{id}" ,methods={"POST","GET"})
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function inscriptionSortie(Sortie $sortie, EntityManagerInterface $entityManager)
    {
        //Récupération  des entités
        $participant = $this->getUser();





        if ($sortie->getEtat()->getId()===1) {

            //Liaison entre sortie et participant
            $sortie -> addIdparticipant($participant);

            $entityManager -> persist($sortie);
            //Envoi vers la base de données
            $entityManager ->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Vous êtes inscrit à la sortie !');
        }
        else {
            // Ajout d'un message de confirmation
            $this->addFlash('warning', 'Vous ne pouvez pas vous inscrire à la sortie !');
        }


        return $this->render('sortie/detailSortie.html.twig', ['sortie'=>$sortie]);
    }

    /**
     * @Route(name="desinscriptionSortie",path="desinscriptionSortie/{id}" ,methods={"POST","GET"})
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function desinscriptionSortie (Sortie $sortie, EntityManagerInterface $entityManager)
    {
        //Récupération  des entités
        $participant = $this->getUser();


        if ($sortie->getEtat()->getId() === 1) {

            //Suppression du participant dans la sortie
            $sortie->removeIdparticipant($participant);

            $entityManager->persist($sortie);

            $entityManager->flush();

            $this->addFlash('success', 'Vous vous êtes désinscrit avec succès !');
        }
        else {

            $this->addFlash('warning', 'La sortie est en cours, impossible de se désinscrire !');
        }

    return $this->render('sortie/detailSortie.html.twig', ['sortie'=>$sortie]);


    }


}
