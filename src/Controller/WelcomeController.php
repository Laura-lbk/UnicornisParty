<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome")
     */
    public function coucou()
    {
        $user = $this->getUser();

        return $this->render('welcome/index.html.twig', [
            'user' => $user
        ]);
    }
}
