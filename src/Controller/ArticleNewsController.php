<?php

//Controlleur de la Gestion des Articles de News

namespace App\Controller;

use App\Entity\ArticleNews;
use App\Repository\ArticleNewsRepository;
use Doctrine\ORM\EntityManagerInterface;;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleNewsController extends AbstractController
{

    //Page d'acceuil des News
    
    /**
     * @Route("/", name="homepage")
     */
    public function index(){
        return $this->render('news/homepage.html.twig');
    }

    
    //Affichage des Artciles News
    public function showNews(PaginatorInterface $paginator, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews=$repo->findAll();

        $articlenewspage=$paginator->paginate(
                $articlenews,
                $request->query->getInt('page',1),
                6 //max de articles par page

        );   

        return $this->render('news/shownews.html.twig',[
            'articlenewspage'=>$articlenewspage
        ]);
    }

    /**
     * @Route("/news/{id}", name="show_news")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews = $repo->find($id);
    	
        return $this->render('news/show.html.twig',[
            'article'=> $articlenews
        ]);
    }


    /////////////////////////////////////////////////////////ADMINSITRATION////////////////////////////////////////////////////////////////////////

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

    	return $this->render('news/add_news.html.twig',[
            'formArticle'=>$form->createView()
        ]);
    }



    //Modifier un Article News
    /**
     * @Route("/admin/news/edit", name="edit_news")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit($id)
    {
        return $this->redirectToRoute('homepage');

    }

    //Choix Suppression Article News
    /**
     * @Route("/admin/news/remove/{id}", name="remove_news_choix")
     * @IsGranted("ROLE_ADMIN")
     */

    public function choixRemoveNews($id){

        return $this->render('news/remove_news.html.twig',[
            'id'=>$id
        ]);
    }

    //Supprimer un Article News
    //Choix Suppression Article News
    /**
     * @Route("/admin/removed/{id}", name="remove_news")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeNews($id)
    {
        //Récupération de l'Article
        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(ArticleNews::class)->find($id);

        //Suppression de l'Article
        $entityManager->remove($news);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

}
