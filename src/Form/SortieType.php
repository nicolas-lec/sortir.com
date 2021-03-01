<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateIntervalToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom de la sortie :',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('dateHeureDebut', DateTimeType::class, [
            'label' => 'Heure de début :',
            'trim' => true,
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);

        $builder ->add('duree', IntegerType::class, [
            'label' => 'Durée de la sortie :',
            'trim' => true,
            'required' => true,
        ]);

        $builder ->add('dateLimiteInscription', DateTimeType::class, [
            'label' => 'Date limite d\'inscription :',
            'trim' => true,
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);

        $builder ->add('nbInscriptionsMax', IntegerType::class, [
            'label' => 'Nombre max d\'inscription :',
            'trim' => true,
            'required' => true,
        ]);

        $builder ->add('infoSortie', TextareaType::class, [
            'label' => 'Description :',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('lieu',EntityType::class, [
        'class' => Lieu::class,
        'choice_label' => 'nom',
        'query_builder' => function(EntityRepository $repository) {
            return $repository->createQueryBuilder('lieu')->orderBy('lieu.nom', 'ASC');
        }
        ]);
//
//        $builder->add('submit', SubmitType::class, [
//            'label' => 'Envoyer',
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
