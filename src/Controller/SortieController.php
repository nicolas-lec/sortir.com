<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Sortie;
use App\Form\SortieType;
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

        

        // Appel à la vue pour afficher le formulaire
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
        $sorties = $entityManager->getRepository('App:Idea')->getAll($category ?? null);

        return $this->render('sortie/sortie.html.twig', ['sorties' => $sorties, 'formSortie' => $formSortie->createView()]);
    }


    /**
     * @Route(name="detailSortie", methods={"GET","POST"}, path="detail/{id}", requirements={"id": "\d+"})
     */
    public function detailSortie(Request $request, EntityManagerInterface $entityManager)
    {
        $id=$request->get('id');

        $sortie = $entityManager -> getRepository('App:Sortie')->getById($id);


        return $this->render('sortie/detailSortie.html.twig', ['sortie'=>$sortie]);
    }


    /**
     * @Route(name="inscriptionSortie", methods={"POST"}, path="inscriptionSortie/{id}")
     */
    public function inscriptionSortie(Request $request, EntityManagerInterface $entityManager) :Response
    {
        //Récupération  des entités
        $idParticipant = $this->getUser();
        $idSortie = $request->get('id');

        //Liaison entre sorties et participant
        $idParticipant->addSortie($idSortie);
        $idSortie -> addIdparticipant($idParticipant);

        $entityManager -> persist($idParticipant);
        $entityManager -> persist($idSortie);

        $entityManager ->flush();

        return new Reponse();
    }
}
