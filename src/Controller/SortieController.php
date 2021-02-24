<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     */
    public function index(): Response
    {
        return $this->render('sortie/sortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }


    /**
     * @Route(name="creationSortie", path="creation-sortie", methods={"GET","POST"})
     */
    public function createSortie(Request $request, EntityManagerInterface $entityManager)
    {
        // Initialiser l'objet mappé au formulaire
        $sortie = new Sortie();

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
     */
    public function detailSortie($id, SortieRepository $repository)
    {
        $sortie =$repository->find($id);

        return $this->render('sortie/detailSortie.html.twig', ['sortie'=>$sortie]);
    }


    /**
     * @Route(name="inscriptionSortie",path="inscriptionSortie/{id}" ,methods={"POST","GET"})
     */
    public function inscriptionSortie(Sortie $sortie, EntityManagerInterface $entityManager)
    {
        //Récupération  des entités
        $participant = $this->getUser();

        //Liaison entre sortie et participant
        $sortie -> addIdparticipant($participant);

        $entityManager -> persist($sortie);
        //Envoi vers la base de données
        $entityManager ->flush();

        return new Response('Insertion réussie!');
    }

    /**
     * @Route(name="desinscriptionSortie",path="desinscriptionSortie/{id}" ,methods={"POST","GET"})
     */
    public function desinscriptionSortie (Sortie $sortie, EntityManagerInterface $entityManager)
    {
        $participant = $this->getUser();

        $sortie -> removeIdparticipant($participant);

        $entityManager -> persist($sortie);

        $entityManager->flush();

        $this->addFlash('success', 'Vous vous êtes désinscrit avec succès !');

        return $this->render('sortie/detailSortie.html.twig', ['sortie'=>$sortie]);


    }


}
