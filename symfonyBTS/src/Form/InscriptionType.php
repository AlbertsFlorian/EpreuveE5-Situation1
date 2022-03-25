<?php

namespace App\Form;

use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class InscriptionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut',  HiddenType::class, [
                'data' => 'E',
            ])
            ->add('formation', EntityType::class, array(
                'class' => 'App\Entity\Formation',
                'choice_label' => 'id',
            ))
            ->add('employe', EntityType::class, array(
                'class' => 'App\Entity\Employe',
                'choice_label' => 'login',
            ))
        ;
    }


}
