<?php

namespace App\Form;

use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('role')
            ->add('password')
            ->add('nom_personnel')
            ->add('postnom_personnel')
            ->add('prenom_personnel')
            ->add('description_personnel')
            ->add('fonction')
            ->add('telephone_personnel')
            ->add('specialite')
            ->add('num_ordre')
            ->add('date_creation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnel::class,
        ]);
    }
}
