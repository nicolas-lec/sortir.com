<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Form\UpdateParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route (name="participant_", path="participant/")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route(path="/register-user", name="registerUser", methods={"GET","POST"})
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
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('participant/profil.html.twig', [
        'Controller_name'=>'ParticipantController'
        ]);
    }

    /**
     * @Route(path="profil/update", name="update")
     * @param Request $request
     * @param EntityManagerInterface $emi
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */

    public function update(Request $request, EntityManagerInterface $emi, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $emi->getRepository(Participant::class)->find($this->getUser()->getId());
        $formUser = $this->createForm(UpdateParticipantType::class, $user);
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            if($formUser->get('passwordPlain')->getData() !== null) {
                $hashed = $passwordEncoder->encodePassword($user, $formUser->get('passwordPlain')->getData());
                $user->setPassword($hashed);
            }
            $emi->persist($user);
            $emi->flush();
            return $this->redirectToRoute("participant_profil", ["id" => $user->getId()]);
        }
        return $this->render("participant/update.html.twig", [
            'formUser' => $formUser->createView(),
            'user' => $user
        ]);
    }
}
