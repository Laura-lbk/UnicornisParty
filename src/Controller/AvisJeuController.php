<?php

namespace App\Controller;

use App\Entity\AvisJeu;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisJeuController extends AbstractController
{
    /**
     * @Route("/avis/jeu", name="avis_jeu")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(AvisJeu::class);

        $articles=$repo->findBy(
            array(),
            array('id'=>'DESC'));

        $articlepage=$paginator->paginate(
                $articles,
                $request->query->getInt('page',1),
                6 //max de articles par page

        );   

        return $this->render('avis_jeu/all.html.twig',[
            'articlepage'=>$articlepage
        ]);
    }
}
