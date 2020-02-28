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

    /**
     * @Route("/questions", name="questions")
     */
    public function questions()
    {
        function Age($date_naissance)
        {
        $am = explode('/', $date_naissance);
        $an = explode('/', date('d/m/Y'));
        if(($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[0] <= $an[0]))) return $an[2] - $am[2];
        return $an[2] - $am[2] - 1;
        }

        $date_naissance='28/04/1999';
        $age=Age($date_naissance);

        return $this->render('questions/questions.html.twig', [
            'age'=>$age
        ]);
    }
}
