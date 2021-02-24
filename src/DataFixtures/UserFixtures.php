<?php
namespace App\DataFixtures;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
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
        $etat1 = new Etat();
        $etat1 ->setLibelle('Publiée');
        $manager->persist($etat1);
        $etat2 = new Etat();
        $etat2 ->setLibelle('En cours');
        $manager->persist($etat2);
        $etat3= new Etat();
        $etat3->setLibelle('Annulé');
        $manager->persist($etat3);
        // $product = new Product();
        // $manager->persist($product);
        for($i=0; $i<20; $i++) {
            $sortie = new Sortie();
            $sortie ->setNom('Sortie '.$i);
            $sortie ->setDateHeureDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-02-24 19:30:00'));
            $sortie ->setDuree(60+$i);
            $sortie ->setDateLimiteInscription(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-02-28 19:30:00'));
            $sortie ->setNbInscriptionsMax(10+$i);
            $sortie->setInfoSortie('Sortie ENI n°'.$i);
            $sortie->setEtat($etat1);
            $manager->persist($sortie);
        }
        for($i=0; $i<20; $i++) {
            $sortie = new Sortie();
            $sortie ->setNom('Sortie '.$i);
            $sortie ->setDateHeureDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-02-24 19:30:00'));
            $sortie ->setDuree(60+$i);
            $sortie ->setDateLimiteInscription(\DateTime::createFromFormat('Y-m-d H:i:s', '2021-02-28 19:30:00'));
            $sortie ->setNbInscriptionsMax(10+$i);
            $sortie->setInfoSortie('Sortie ENI n°'.$i);
            $sortie->setEtat($etat2);
            $manager->persist($sortie);
        }
        $manager->flush();
    }
}

