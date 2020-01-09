<?php

//Controlleur de la Gestion des Articles de News

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;;
use App\Entity\ArticleNews;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Repository\ArticleNewsRepository;
use Symfony\Component\Routing\Annotation\Route;

class ArticleNewsController extends AbstractController
{

    //Affichage des Artciles News sur la page d'acceuil
    
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews=$repo->findAll();

        return $this->render('news/home.html.twig',[
            'controller_name'=>'ArticleNewsController',
            'articlenews'=> $articlenews
        ]);
    }

    //Création d'un nouvel Article

    /**
     * @Route("/admin/news/add", name="add_news")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, EntityManagerInterface $manager)
    { 

        $article= new ArticleNews();

        $form = $this->createFormBuilder($article)
                     ->add('titre')
                     ->add('contenu')
                     ->add('image')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $article->setDateCreation(new \DateTime());

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('homepage');
        }

    	return $this->render('news/create.html.twig',[
            'formArticle'=>$form->createView()
        ]);
    }

    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews = $repo->find($id);

    	
        return $this->render('news/show.html.twig',[
            'articlenews'=> $articlenews
        ]);
    }

    //Modifier un Article News

    public function edit($id)
    {
    	return new Response('<h1>Modifier l\'article ' .$id. '</h1>');
    }

    //Supprimer un Article News

    public function remove($id)
    {
    	return new Response('<h1>Supprimer l\'article ' .$id. '</h1>');
    }
}
