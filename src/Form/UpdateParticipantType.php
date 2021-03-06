<?php

namespace App\Form;

use App\Entity\Participant;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;


class UpdateParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudoPlain', TextType::class, [
                'label' => 'Pseudo :',
                'mapped' => false,
                'attr' => [
                    'maxlength' => 50
                ]
            ])
            ->add('passwordPlain', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Regex(['pattern' => '/^(?=.*\d)(?=.*[A-Z])(?=.*[@#$%])(?!.*(.)\1{2}).*[a-z]/m',
                        'match' => true,
                        'message' => "Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole."
                    ])
                ],
                'first_options' => [
                    'label' => 'Nouveau mot de passe :',
                    'attr' => [
                        'maxlength' => 50
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation :',
                    'attr' => [
                        'maxlength' => 50
                    ]
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom :',
                'attr' => [
                    'maxlength' => 50
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :',
                'attr' => [
                    'maxlength' => 50
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone :',
                'required' => false,
                'attr' => [
                    'maxlength' => 10
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Mail :',
                'attr' => [
                    'maxlength' => 50
                ]
            ])
             ->add('imageUser', FileType::class, [
                 'label' => 'Photo de profil',
                 'mapped' => false,
                 'required' => false,
                 'constraints' => [
                     new File([
                         'maxSize' => '1000k',
                         'mimeTypes' => [
                             'image/jpeg',
                             'image/png',
                             'image/svg+xml',
                         ],
                         'mimeTypesMessage' => 'Pour la photo de profil il faut que le fichier soit'.
                             'sous l\'un des formats suivant : JPEG, PNG, SVG',
                     ])
                 ],
             ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event){
                $p = $event->getData();
                $form = $event->getForm();

                if(empty($p->getPseudo())){
                    return;
                }

                $form->get('pseudoPlain')->setData($p->getPseudo());

            })
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
