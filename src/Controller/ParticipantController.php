<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Site;
use App\Form\ParticipantType;
use App\Form\RegisterType;
use App\Form\UpdateParticipantType;
use App\Repository\ParticipantRepository;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="L'accès est réservé au administrateur")
     */
    public function inscription(EntityManagerInterface $entityManager,
                                Request $request,
                                UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->get('admin')->getData();
            $imageFile = $form->get('imageUser')->getData();
            if ($admin==true)
                $participant->setRoles(['ROLE_ADMIN']);
            else
                $participant->setRoles(['ROLE_USER']);


            //Tentative d'ajout d'une photo de profil
            if($imageFile) {
                $originalFileName = pathinfo($imageFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove;'
                .'Lower()', $originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move (
                        $this -> getParameter('images_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
                $participant -> setImageFileName($newFileName);
            }
            $participant->setPassword($passwordEncoder->encodePassword($participant, $participant->getPlainPassword()));
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'L\'inscritpion a été un succès ! ;)');


           return $this->redirectToRoute('participant_inscription');

        }
        return $this->render('participant/register.html.twig',
            ['participantForm' => $form->createView()]
        );
    }

    /**
     * @Route(path="profil", name="profil")
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function profil()
    {
            return $this->render('participant/profil.html.twig', [
                'Controller_name' => 'ParticipantController'
            ]);
    }

    /**
     * @Route(path="profil/update", name="update")
     * @param Request $request
     * @param EntityManagerInterface $emi
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */

    public function update(Request $request, EntityManagerInterface $emi, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $emi->getRepository(Participant::class)->find($this->getUser()->getId());
        $formUser = $this->createForm(UpdateParticipantType::class, $user);
        $formUser->handleRequest($request);
        $oldImage = $user -> getImageFileName();
        $filename =  $this -> getParameter('images_directory').'/'.$oldImage;
        //dd($filename,$oldImage);


        if ($formUser->isSubmitted() && $formUser->isValid()) {
            if ($formUser->get('passwordPlain')->getData() !== null) {
                $hashed = $passwordEncoder->encodePassword($user, $formUser->get('passwordPlain')->getData());
                $user->setPassword($hashed);
            }
                $newImage = $formUser->get('imageUser')->getData();
                //dd($filename,$oldImage);
                //Tentative de remplacement de la photo de profil
                if($newImage) {
                    $originalFileName = pathinfo($newImage->getClientOriginalName(),PATHINFO_FILENAME);
                    $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove;'
                        .'Lower()', $originalFileName);
                    $newFileName = $safeFileName.'-'.uniqid().'.'.$newImage->guessExtension();

                    try {
                        $newImage->move (
                            $this -> getParameter('images_directory'),
                            $newFileName
                        );
                    } catch (FileException $e) {

                    }
                    $user -> setImageFileName($newFileName);

                    if (!empty($oldImage) && file_exists($filename)) {
                        unlink($filename);
                    }
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
    /**
     * @Route(path="profilParticipant/{id}", name="profilParticipant", methods={"GET","Post"}, requirements={"id": "\d+"})
     * @IsGranted("ROLE_USER", statusCode=404, message="L'accès est réservé au personne inscrite")
     */
    public function profilParticipant($id, ParticipantRepository $repository)
    {
        $p = $repository->find($id);
        return $this->render('participant/profilParticipant.html.twig', [
            'p'=>$p,
            'Controller_name' => 'ParticipantController'
        ]);
    }
}
