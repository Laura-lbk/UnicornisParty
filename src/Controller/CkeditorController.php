<?php

namespace App\Controller;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CkeditorController extends AbstractController
{
    /**
     * @Route("/ckeditor", name="ckeditor")
     */
    public function ckeditor()
    {

        $form = $this->createFormBuilder()
            ->add('content',CKEditorType::class,[
                'config'=>[
                    'uiColor'=>'#fff',
                    'toolbar'=>'full',
                    'required'=>true
                ]
            ])
            ->getForm();

        return $this->render('ckeditor/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
