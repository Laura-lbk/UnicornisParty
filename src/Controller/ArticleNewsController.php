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

        $articlenews=$paginator->paginate(
                $repo->findAll(), //on récupère toutes les données du repertoir
                $request->query->getInt('page',1),
                6 //max de articles par page

        );   

        return $this->render('news/shownews.html.twig',[
            'articlenews'=> $articlenews,
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

    //Supprimer un Article News
    /**
     * @Route("/admin/news/delete/{id}", name="delete_news")
     * @IsGranted("ROLE_ADMIN")
     */
    public function remove($id)
    {


        return $this->redirectToRoute('homepage');
    }

}
