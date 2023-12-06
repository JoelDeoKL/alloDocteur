<?php

namespace App\Form;

use App\Entity\Fiche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_naissance')
            ->add('adresse_patient')
            ->add('date_entre')
            ->add('date_sortie')
            ->add('nom_conjoint')
            ->add('service', ChoiceType::class, [
                'choices' => [
                    'Stromato' => 'Stromato',
                    'Pédiatrie' => 'Pédiatrie',
                    'Gyneco' => 'Gyneco',
                    'Medecine interne' => 'Medecine interne',
                    'Dentisterie' => 'Dentisterie',
                    'Ophtamoligie' => 'Ophtamoligie',
                ],
            ])
            ->add('observation')
            ->add('dossier')
            ->add('patient')
            ->add('personnel')
            ->add('editer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
}
