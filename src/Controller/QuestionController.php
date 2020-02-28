<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('questions/contact.html.twig', [
            
        ]);
    }

     /**
     * @Route("/materiel", name="materiel")
     */
    public function materiel()
    {
        return $this->render('questions/materiel.html.twig', [
            
        ]);
    }
}
