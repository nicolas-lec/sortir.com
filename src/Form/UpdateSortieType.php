<?php

namespace App\Form;


use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :',
                'trim' => true,
                'required' => true,
                'attr' => [
                    'maxlength' => 50
                ]
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'trim' => true,
            'required' => true,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',


            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite dinscription :',
                'years' => range(2020,2030),
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'trim' => true,
                'required' => true,
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de place :',
                'trim' => true,
                'required' => true,
                'attr' => [
                    'min' => '1',
                    'max' => '5000'
                ]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée :',
                'trim' => true,
                'required' => true,
            ])
            ->add('infoSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                'required' => false,
                'attr' => [
                    'maxlength' => 255
                ]
            ])

            ->add('lieu',EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('lieu')->orderBy('lieu.nom', 'ASC');
                }
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
