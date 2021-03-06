<?php

//Controlleur de la Gestion des Articles de News

namespace App\Controller;

use App\Form\NewsType;
use App\Service\Flatener;
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
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);

        $articlenews=$repo->findBy(
            array(),
            array('id'=>'DESC'));

        $articlenewspage=$paginator->paginate(
                $articlenews,
                $request->query->getInt('page',1),
                6 //max de articles par page

        );   

        return $this->render('news/homepage.html.twig',[
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
    public function add(Request $request, EntityManagerInterface $manager, Flatener $flatener)
    { 
        $article= new ArticleNews();

        $form = $this->createForm(NewsType::class, $article);

        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository(ArticleNews::class);
        $allfanart=$repo->findImage();

        $Filename_img="image.jpeg";
        $counter=0;
        $again=1;
    
            //on aplatit le tableau
        $flatarray=$flatener->flatten($allfanart);
    
            //Si le nom existe déjà on aloue un num random derrière le nom existant
        do{
            $numrandom=rand(0,9);//generation d'un num random
            $Filename_clean=substr($Filename_img, 0, -5);// on enlève le .jpeg
            $Filename_img=$Filename_clean.$numrandom.'.jpeg';//nom=nom+num+jpeg

            $counter=$counter+1;
                
            if(in_array($Filename_img, $flatarray)){ //si le nom existe déjà dans la BDD
                $again=1;
            }else{
                $again=0;
            }
    
            }while($again==1);

        if($form->isSubmitted()&& $form->isValid()){
            $article->setDateCreation(new \DateTime());

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                try {
                    $imageFile->move(
                        $this->getParameter('image_news_directory'),
                        $Filename_img
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $article->setImage($Filename_img);
            }

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
     * @Route("/admin/news/edit/{id}", name="edit_news")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit($id, Request $request)
    {
        //Récupération du Article News
        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(ArticleNews::class)->find($id);
        $titre=$news->getTitre();
        $contenu=$news->getContenu();
        $image=$news->getImage();

        $Filename_cover=$image;

        //Création d'un Formulaire
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        //Modification du Article News
        if($form->isSubmitted()&& $form->isValid()){
            $news->setTitre($form['titre']->getData());
            $news->setContenu($form['contenu']->getData());

            $cover=$form['image']->getData();
            if($cover!=NULL){
                
                $coverFile = $form->get('image')->getData();

                if ($coverFile) {
                try {
                    $coverFile->move(
                        $this->getParameter('image_news_directory'),
                        $Filename_cover
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $news->setImage($Filename_cover);
                }
            }
                
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($news);
            $manager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('news/edit_news.html.twig',[
            'id'=>$id,
            'contenu'=>$contenu,
            'form'=>$form->createView()
        ]);

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
