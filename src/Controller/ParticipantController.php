<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route (name="participant_", path="participant/")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route(path="inscription", name="inscription", methods={"GET","POST"})
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager, LoginAuthenticator $login, GuardAuthenticatorHandler $guard, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $participant->setPassword($passwordEncoder->encodePassword($participant, $participant->getPlainPassword()));

            $participant->setRoles(['ROLE_USER']);

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'L\'inscritpion a été un succès ! ;)');


            return $guard->authenticateUserAndHandleSuccess($participant, $request, $login, 'app_user_provider' );
        }

        return $this->render('participant/register.html.twig', ['participantForm' => $form->createView()]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('participant/profil.html.twig', [
            'edit' => false,
            'edit_password' => false,
            'form' => null,
            'page_name' => 'Profil'
        ]);
    }

}
