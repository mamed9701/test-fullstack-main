<?php

namespace App\Form;

use App\Entity\Clocking;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;

class CreateClockingType extends
    AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     *
     * @return void
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array                $options
    ) : void {

        $builder
        /**
         * Champ de formulaire correpondant 
         * à la propriété clockingProject
         * Liste déroulante des chantiers
         */
        ->add('clockingProject', EntityType::class, [
            'class' => Project::class,
            'choice_label' => 'name',
            'label' => 'entity.Project.name'
        ])
        /**
         * Champ de formulaire correpondant 
         * à la propriété duration
         * Durée passée sur le chantier
         */
        ->add('duration', IntegerType::class, [
            'label' => 'Durée : ',
            'constraints' => [
                new LessThanOrEqual(value:10),
                new Positive()
            ]
        ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(
            [
                'data_class' => Clocking::class,
            ]
        );
    }
}
