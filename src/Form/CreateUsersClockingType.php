<?php

namespace App\Form;

use App\Entity\Clocking;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;

class CreateUsersClockingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        /**
         * Champ de formulaire correpondant 
         * à la propriété clockingUsers
         * Liste déroulante avec choix multiple
         * des collaborateurs.
         */
        ->add('clockingUsers', EntityType::class, [
            'class'        => User::class,
            'choice_label' => 'fullName',
            'label'        => 'entity.Clocking.clockingUser',
            'multiple' => true,  // Permet au champ d'avoir des choix multiples
            'expanded' => false,
            'attr' => [
                'class' => 'select2' // Utilisation de la classe css et js de Select2
            ]
        ])

        /**
         * Champ de formulaire correpondant 
         * à la propriété date
         * Date du pointage
         */
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date du pointage : ',
            'mapped' => false, // Le champ n'est pas directement lié à l'entité
            'constraints' => [
                new LessThanOrEqual('today'),
            ]
        ])
        /**
         * Champ de formulaire correpondant 
         * à la propriété clockingProject
         * Liste déroulante avec choix multiple
         * des chantiers.
         */
        ->add('clockingProject', EntityType::class, [
            'class' => Project::class,
            'choice_label' => 'name',
            'label'        => 'entity.Clocking.clockingProject',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
        ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clocking::class,
        ]);
    }
}
