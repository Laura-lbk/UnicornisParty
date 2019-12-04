<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;;
use App\Entity\ArticleNews;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use App\Repository\ArticleNewsRepository;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews=$repo->findAll();

        return $this->render('blog/home.html.twig',[
            'controller_name'=>'BlogController',
            'articlenews'=> $articlenews
        ]);
    }

    public function new(Request $request, EntityManagerInterface $manager)
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

    	return $this->render('blog/create.html.twig',[
            'formArticle'=>$form->createView()
        ]);
    }

    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews = $repo->find($id);

    	
        return $this->render('blog/show.html.twig',[
            'articlenews'=> $articlenews
        ]);
    }

    public function edit($id)
    {
    	return new Response('<h1>Modifier l\'article ' .$id. '</h1>');
    }

    public function remove($id)
    {
    	return new Response('<h1>Supprimer l\'article ' .$id. '</h1>');
    }
}
