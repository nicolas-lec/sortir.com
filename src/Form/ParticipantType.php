<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pseudo', TextType::class,
            [
                'label' => 'Pseudo :',
                'trim' => true,
                'required' => true,
            ]);
        /*
        $builder->add('roles', ChoiceType::class, array(

            'label' => 'Role de l\'utilisateur :',
            'choices' => ['ROLE ADMIN' => 'ROLE_ADMIN'],
            'expanded' => true,
            'multiple' => true

        ));
*/
        $builder-> add('admin', CheckboxType::class, [
            'label' => 'Administrateur :',
            'mapped' => false,
            'required'=>false
        ]);
        $builder->add('password', PasswordType::class,
            [
                'label' => 'Mots de passe :',
                'trim' => true,
                'required' => true,
            ]);
        $builder->add('nom', TextType::class,
            [
                'label' => 'Nom :',
                'trim' => true,
                'required' => true,
            ]);
        $builder->add('prenom', TextType::class,
            [
                'label' => 'Prénom :',
                'trim' => true,
                'required' => true,
            ]);
        $builder->add('telephone', NumberType::class,
            [
                'label' => 'Téléphone :',
                'trim' => true,
                'required' => true,
            ]);
        $builder->add('mail', EmailType::class,
            [
                'label' => 'E-mail :',
                'trim' => true,
                'required' => true,
            ]);
        $builder->add('actif', ChoiceType::class,
            [
                'label' => 'Compte Utilisateur :',
                'choices' =>
                    [
                        'Activer' => 1,
                        'Désactiver' => 0
                    ],
                'expanded' => true,


            ]);

        $builder->add('site', EntityType::class, [
            'class' => Site::class,
            'choice_label' => 'nom',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('site')->orderBy('site.nom');
            }
        ]);



        $builder->add('submit', SubmitType::class,
            [
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
