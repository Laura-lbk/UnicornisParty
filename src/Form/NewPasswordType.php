<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class NewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldpassword', PasswordType::class, ['label' => 'Mot de Passe Actuel'])
            ->add('newpassword', PasswordType::class, ['label' => 'Nouveau Mot de Passe'])
            ->add('confirm_newpassword', PasswordType::class, ['label' => 'Retapez votre Nouveau Mot de Passe'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
