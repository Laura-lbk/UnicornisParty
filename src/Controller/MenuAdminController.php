<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MenuAdminController extends AbstractController
{

    /**
    * @Route("/admin", name="menu_admin")
    * @IsGranted("ROLE_ADMIN")
    */
    public function menuAdmin()
    {
        return $this->render('menu_admin/acceuil.html.twig');
    }

    /**
    * @Route("/admin/news", name="menu_news")
    * @IsGranted("ROLE_ADMIN")
    */
    public function menuNews()
    {
        return $this->render('menu_admin/menu_news.html.twig');
    }
}
