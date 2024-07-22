<?php

namespace App\Form;

use App\Entity\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqualValidator;
use Symfony\Component\Validator\Constraints\Regex;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        /**
         * Champ de formulaire de type Texte
         * Saisie du Prénom
         */
        ->add('firstName', TextType::class, [
            'label' => 'entity.User.firstName',
            'constraints' => [
                new Regex(
                    pattern : "/^[a-zA-Z_ ]+$/i",
                    htmlPattern : "[a-zA-Z_ ]+",
                    match : true
                ),
            ]
        ])
        /**
         * Champ de formulaire de type Texte
         * Saisie du Nom
         */
        ->add('lastName', TextType::class, [
            'label' => 'entity.User.lastName',
            'constraints' => [
                new Regex(
                    pattern : "/^[a-zA-Z_ ]+$/i",
                    htmlPattern : "[a-zA-Z_ ]+",
                    match : true
                )
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
            'mapped' => false, // This field is not directly mapped with an Entity
            'constraints' => [
                new LessThanOrEqual('today'),
            ]

        ])
        /**
         * Champ de formulaire de type collection
         * correspondant à la propriété 'clockings'
         * Fais réference au formulaire enfant 
         * 'CreateClockingType'
         * Sert à inclure les champs du 
         * formulaire enfant à ce formulaire parent
         * 
         */
        ->add('clockings', CollectionType::class, [
            'entry_type' => CreateClockingType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
