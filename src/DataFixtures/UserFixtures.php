<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //insertion des différents campus
        $campus1 = new Site();
        $campus1->setNom('Campus Angers');
        $manager->persist($campus1);

        $campus2 = new Site();
        $campus2->setNom('Campus Nantes');
        $manager->persist($campus2);

        $campus3 = new Site();
        $campus3->setNom('Campus Rennes');
        $manager->persist($campus3);

        // insertion de participant en tant qu'Admin
        for ($i = 0; $i < 5; $i++) {
            $participant = new Participant();
            $participant->setPseudo('Admin' . $i);
            $participant->setMail('admin'. $i .'@mail.fr');
            $participant->setNom('Dupont' . $i);
            $participant->setPrenom('Gille' . $i);
            $participant->setTelephone('0647858095');
            $participant->setActif(1);
            $participant->setRoles(["ROLE_ADMIN"]);
            $password = $this->encoder->encodePassword($participant, ' ');
            $participant->setPassword($password);
            $participant->setSite($campus2);
            $manager->persist($participant);
        }
        $manager->flush();
        for ($i = 0; $i < 3; $i++) {
            $participant = new Participant();
            $participant->setPseudo('Administrateur' . $i);
            $participant->setMail('administrateur'. $i .'@mail.fr');
            $participant->setNom('Leclerc' . $i);
            $participant->setPrenom('Nicolas' . $i);
            $participant->setTelephone('0647856205');
            $participant->setActif(1);
            $participant->setRoles(["ROLE_ADMIN"]);
            $password = $this->encoder->encodePassword($participant, ' ');
            $participant->setPassword($password);
            $participant->setSite($campus1);
            $manager->persist($participant);
        }
        $manager->flush();

        //insertion de participant en tant qu'utilisateur
        for ($i = 0; $i < 20; $i++) {
            $participant = new Participant();
            $participant->setPseudo('User'.$i);
            $participant->setMail('user'. $i .'@mail.fr');
            $participant->setNom('Ligonnes' . $i);
            $participant->setPrenom('Xavier' . $i);
            $participant->setTelephone('0647856225');
            $participant->setActif(1);
            $participant->setRoles(["ROLE_USER"]);
            $password = $this->encoder->encodePassword($participant, ' ');
            $participant->setPassword($password);
            $participant->setSite($campus3);
            $manager->persist($participant);
        }
        $manager->flush();

        $participant1 = new Participant();
        $participant1->setPseudo('AffaireSK1');
        $participant1->setMail('sk1@mail.fr');
        $participant1->setNom('Georges');
        $participant1->setPrenom('Guy');
        $participant1->setTelephone('0660656225');
        $participant1->setActif(1);
        $participant1->setRoles(["ROLE_USER"]);
        $password = $this->encoder->encodePassword($participant1, ' ');
        $participant1->setPassword($password);
        $participant1->setSite($campus1);
        $manager->persist($participant1);

        $participant2 = new Participant();
        $participant2->setPseudo('DonaldTrump');
        $participant2->setMail('Fakenews@mail.fr');
        $participant2->setNom('Trump');
        $participant2->setPrenom('Donald');
        $participant2->setTelephone('0660725625');
        $participant2->setActif(1);
        $participant2->setRoles(["ROLE_USER"]);
        $password = $this->encoder->encodePassword($participant2, ' ');
        $participant2->setPassword($password);
        $participant2->setSite($campus2);
        $manager->persist($participant2);

        $participant3 = new Participant();
        $participant3->setPseudo('Brandao');
        $participant3->setMail('jaipastouche@mail.fr');
        $participant3->setNom('Brandao');
        $participant3->setPrenom('Everson');
        $participant3->setTelephone('0645786225');
        $participant3->setActif(1);
        $participant3->setRoles(["ROLE_USER"]);
        $password = $this->encoder->encodePassword($participant3, ' ');
        $participant3->setPassword($password);
        $participant3->setSite($campus3);
        $manager->persist($participant3);

        $manager->flush();

        //insertion des différents états d'une sortie
        $etat1 = new Etat();
        $etat1->setLibelle('Publiée');
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle('Brouillon');
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Annulé');
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('Archivé');
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Evenement en cours');
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Evenement terminée');
        $manager->persist($etat6);
        $manager->flush();


        //insertion des villes disponibles pour les sorties
        $ville1 = new Ville();
        $ville1->setNom('Angers');
        $ville1->setCodePostal(49000);
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('Nantes');
        $ville2->setCodePostal(44000);
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('Rennes');
        $ville3->setCodePostal(35000);
        $manager->persist($ville3);

        // insertion de lieu pour les sorties
        $lieu1 = new Lieu();
        $lieu1->setNom('Théâtre Beaumarchais');
        $lieu1->setRue('Victor Hugo');
        $lieu1->setLatitude(80.6);
        $lieu1->setLongitude(63.5);
        $lieu1->setVille($ville1);
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setNom('Cinéma CGR');
        $lieu2->setRue('Lamartine');
        $lieu2->setLatitude(10.6);
        $lieu2->setLongitude(15.8);
        $lieu2->setVille($ville2);
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom('Bar Délirium');
        $lieu3->setRue('Victor Hugo');
        $lieu3->setLatitude(3.5);
        $lieu3->setLongitude(12.5);
        $lieu3->setVille($ville3);
        $manager->persist($lieu3);

        $manager->flush();

        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 5; $i++) {
            $sortie = new Sortie();
            $sortie->setNom('Sortie ' . $i);
            $sortie->setDateHeureDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-05-30 19:30:00'));
            $sortie->setDuree(60 + $i);
            $sortie->setDateLimiteInscription(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-05-29 19:30:00'));
            $sortie->setNbInscriptionsMax(10 + $i);
            $sortie->setInfoSortie('Sortie ENI n°' . $i);
            $sortie->setEtat($etat1);
            $sortie->setOrganisateur($participant1);
            $sortie->setSite($campus3);
            $sortie->setLieu($lieu2);
            $manager->persist($sortie);
        }

        for ($i = 0; $i < 3; $i++) {
            $sortie = new Sortie();
            $sortie->setNom('Rendez-vous au bar ' . $i);
            $sortie->setDateHeureDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-04-30 19:30:00'));
            $sortie->setDuree(60 + $i);
            $sortie->setDateLimiteInscription(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-04-29 19:30:00'));
            $sortie->setNbInscriptionsMax(10 + $i);
            $sortie->setInfoSortie('On se retrouve au bar n°' . $i);
            $sortie->setEtat($etat2);
            $sortie->setOrganisateur($participant2);
            $sortie->setSite($campus3);
            $sortie->setLieu($lieu3);
            $manager->persist($sortie);
        }

        for ($i = 0; $i < 2; $i++) {
            $sortie = new Sortie();
            $sortie->setNom('Conférence sur Turbo Pascal n° ' . $i);
            $sortie->setDateHeureDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-04-30 19:30:00'));
            $sortie->setDuree(60 + $i);
            $sortie->setDateLimiteInscription(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-04-29 19:30:00'));
            $sortie->setNbInscriptionsMax(10 + $i);
            $sortie->setInfoSortie('Une sortie qui parle de l\'émission tubo et Blaise Pascal n°' . $i);
            $sortie->setEtat($etat2);
            $sortie->setOrganisateur($participant3);
            $sortie->setSite($campus3);
            $sortie->setLieu($lieu1);
            $manager->persist($sortie);
        }


        $manager->flush();
    }

}


