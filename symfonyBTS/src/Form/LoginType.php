<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as SFType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('mdp', PasswordType::class, [
                'required' => true,
                'label' => 'Entrez votre password',
                ])
            ->add('Connectez-vous', SFType\SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
        */
    }
}
