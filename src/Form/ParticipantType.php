<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pseudo', TextType::class, [
            'label' => 'Pseudo :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('roles', CollectionType::class, [
            'label' => 'Roles :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('password', PasswordType::class, [
            'label' => 'Mots de passe :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('nom', TextType::class, [
            'label' => 'Nom :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('prenom', TextType::class, [
            'label' => 'Prénom :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('telephone', NumberType::class, [
            'label' => 'Téléphone :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('mail', EmailType::class, [
            'label' => 'E-mail :',
            'trim' => true,
            'required' => true,
        ]);
        $builder->add('actif', RadioType::class, [
            'label' => 'Actif :',
            'required' => true,
        ]);
        $builder->add('submit', SubmitType::class, [
            'label' => 'Envoyer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
