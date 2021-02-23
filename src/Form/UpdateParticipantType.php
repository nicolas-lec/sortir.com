<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;


class UpdateParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :',
                'attr' => [
                    'maxlength' => 50
                ]
            ])
            ->add('passwordPlain', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length(['min' => 8]),
                    new Regex([pattern =>'/^(?=.*\d)(?=.*[A-Z])(?=.*[@#$%])(?!.*(.)\1{2}).*[a-z]/m',
                        'match' =>true,
                        message => "Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole."
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
