<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route(path="register-user", name="registerUser", methods={"GET","POST"})
     */
    public function inscription(EntityManagerInterface $entityManager): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class);

        if  ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();
        }
        return $this->render('participant/register.html.twig',
            ['participantForm' => $form->createView()]
        );
    }
    /**
     * @Route("/profil/detail/{id}", name="profil_detail")
     * @param $id
     * @param EntityManagerInterface $emi
     * @return Response
     */
    public function profil($id,EntityManagerInterface $emi)
    {

        $user = $emi->getRepository(Participant::class)->find($id);
        if ($user==null) {
            throw $this->createNotFoundException("L'utilisateur est absent dans la base de données. Essayez un autre ID !");
        }
        return $this->render('participant/profil.html.twig', [
            'controller_name' => 'ParticipantController',
            'user_profil' => $user
        ]);
    }
}
