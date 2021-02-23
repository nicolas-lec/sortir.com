<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=0; $i<20; $i++) {
            $participant = new Participant();
            $participant->setPseudo('participant'.$i);
            $participant->setMail('participant@mail.fr');
            $participant->setNom('Dupont'.$i);
            $participant->setPrenom('Gille'.$i);
            $participant->setTelephone('0647858095');
            $participant->setActif(1);
            $participant->setRoles(["ROLE_ADMIN"]);
            $password=$this->encoder->encodePassword($participant,'Pa$$w0rd');
            $participant->setPassword($password);
            $manager->persist($participant);
        }

        $manager->flush();
    }

    public function sortie(ObjectManager $manager) {
        for($i=0; $i<20; $i++) {
            $sortie = new Sortie();
            $sortie ->setNom('Sortie '.$i);
            $sortie ->setDateHeureDebut(new \DateTime('20/02/2021'));
            $sortie ->setDuree(60+$i);
            $sortie ->setDateLimiteInscription(new \DateTime('25/03/2021'));
            $sortie ->setNbInscriptionsMax(10+$i);
            $sortie->setInfoSortie('Sortie ENI n°'.$i);
            $manager->persist($sortie);
        }
        $manager->flush();
    }
}


