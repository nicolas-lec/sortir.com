<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $sortie->setDateLimiteInscription(new \DateTime('now')) ;

        // Création du formulaire
        $form = $this->createForm(SortieType::class, $sortie);

        // Récupération des données de la requête HTTP (Navigateur) au formulaire
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            //Envoi de l'id de l'utilisateur
            $participant = $this->getUser();
            $sortie->setOrganisateur($participant);

            //Récupération du campus de l'utilisateur créant la sortie
            $campus = $this->getUser()->getSite();
            $sortie->setSite($campus);

            //L'objet request permet de récupérer la value bouton provenant du twig
            $env = $request->request->get('envoyer');
            $enregister = 'enregistrer';
            $publier = 'publier';
            //dd($env);
            //Condition permettant d'enregistrer la sortie sous différents états
            if ($env === $publier) {
                //Envoi de l'état de la sortie publiée
                $etat = $entityManager->getRepository('App:Etat')->findOneBy(['id'=>1]);
                $sortie->setEtat($etat);
                // Ajout d'un message de confirmation
                $this->addFlash('success', 'Votre sortie a été ajoutée avec succès !');
               //dd($sortie);

            }
            elseif ($env === $enregister) {
                //Envoi de l'état de la sortie enregistrée
                $etat = $entityManager->getRepository('App:Etat')->findOneBy(['id'=>2]);
                $sortie->setEtat($etat);
                // Ajout d'un message de confirmation
                $this->addFlash('success', 'Votre sortie a été enregistrée avec succès !');

            }
            //dd($sortie);
            // Insertion de l'objet en BDD
            $entityManager->persist($sortie);

            // Validation de la transaction
            $entityManager->flush();

            return $this->redirectToRoute('home_home');



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

        if ($sortie->getEtat()->getId()===1 && $sortie ->getDateLimiteInscription() > new \DateTime('now')) {

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

    /**
     * @Route(name="deleteSortie", path="deleteSortie/{id}",  methods={"POST","GET"})
     */
    public function deleteSortie ($id, Sortie $sortie, Request $request, EntityManagerInterface $entityManager)
    {


        // Création du formulaire
        $formSortie = $this->createForm('App\Form\AnnuleSortieType', $sortie);

        // Récupération des données de la requête HTTP (Navigateur) au formulaire
        $formSortie->handleRequest($request);

        // Vérification de la soumission du formulaire
        if ($formSortie->isSubmitted() && $formSortie->isValid())
        {
            $annulation = $formSortie['descriptionAnnul']->getData();
            $sortieId =(int)$id;
            $sortie = $entityManager->getRepository('App:Sortie')->findOneBy(['id'=> $sortieId]);
            $etat = $entityManager->getRepository('App:Etat')->findOneBy(['id' => 3]);
            $sortie->setEtat($etat);
            $sortie->setDescriptionAnnul($annulation);

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_detailSortie', ['id' => $sortie->getId()]);

        }
        return $this->render("sortie/annulation.sortie.html.twig",['descriptionAnnul'=>$formSortie->createView()]);

    }

    /**
     * Modifier une sortie
     * @Route("detail/{id}/edit", name="edit", methods={"POST","GET"})
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé a l'organisateur")
     */
    public function updateSortie ($id, Request $request, EntityManagerInterface $em)
    {
        $sortie = $em->getRepository('App:Sortie')->findOneBy(['id'=> $id]);
        $formSortie = $this->createForm('App\Form\UpdateSortieType',$sortie);
        $formSortie->handleRequest($request);
        if($formSortie->isSubmitted() && $formSortie->isValid())
        {
            $user=$this->getUser();
            if($user == null || $user->getId() != $sortie->getOrganisateur()->getId()){
                return $this->redirectToRoute("home_home");
            }
            $env = $request->request->get('modifier');
            $publier = 'publier';
            $enregistrer = 'enregistrer';
            if($env === $publier){
                $etat = $em->getRepository('App:Etat')->findOneBy(['id'=>1]);
                $sortie->setEtat($etat);
                // Ajout d'un message de confirmation
                $this->addFlash('success', 'Votre sortie a été modifiée et publier avec succès !');
            }elseif($env === $enregistrer){
                $etat = $em->getRepository('App:Etat')->findOneBy(['id'=>2]);
                $sortie->setEtat($etat);
                // Ajout d'un message de confirmation
                $this->addFlash('success', 'Votre sortie a été modifiée et mis en brouillons avec succès !');
            }
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute("sortie_detailSortie",
                ['id' => $sortie->getId()]);
        }
        return $this->render("sortie/edit.html.twig", [
            'formSortie' => $formSortie->createView(),
            'sortie' => $sortie
        ]);


    }


}
