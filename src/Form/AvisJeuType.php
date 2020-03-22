<?php

namespace App\Form;

use App\Entity\AvisJeu;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AvisJeuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenu',CKEditorType::class,[
                'config'=>[
                    'uiColor'=>'#ffffffff',
                    'toolbar'=>'full',
                    'required'=>true
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'choices'  => [
                    'Jeu Divers' => 'Divers',
                    'Jeu de Chevaux' => 'Cheval',
                ],
            ])
            ->add('cover', FileType::class, [
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
            'data_class' => AvisJeu::class,
        ]);
    }
}
