<?php

namespace App\Form;

use App\Entity\Fanart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FanArtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '40M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'format non valide',
                ])
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fanart::class,
        ]);
    }
}
