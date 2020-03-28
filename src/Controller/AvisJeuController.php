<?php

namespace App\Controller;

use App\Entity\AvisJeu;
use App\Form\AvisJeuType;
use App\Service\Flatener;
use App\Repository\AvisJeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisJeuController extends AbstractController
{
    /**
     * @Route("/avis/jeu", name="avis_jeu")
     */
    public function indexDivers(PaginatorInterface $paginator, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(AvisJeu::class); 

        $articles=$repo->findBy(
            ['categorie' => 'Divers'],
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

     /**
     * @Route("/avis/jeu_equitation", name="avis_jeu_cheval")
     */
    public function indexCheval(PaginatorInterface $paginator, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(AvisJeu::class); 

        $articles=$repo->findBy(
            ['categorie' => 'Cheval'],
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

    /**
     * @Route("/avis/{id}", name="show_avis")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(AvisJeu::class);

        $avis = $repo->find($id);
    	
        return $this->render('avis_jeu/show.html.twig',[
            'avis'=> $avis
        ]);
    }

     /**
     * @Route("/admin/avis/add", name="add_avis")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, EntityManagerInterface $manager, Flatener $flatener)
    {   
        $avis = new AvisJeu();
        $form = $this->createForm(AvisJeuType::class, $avis);
        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository(AvisJeu::class);
        $allcover=$repo->findCover();

        $Filename_cover="cover.jpeg";
        $counter=0;
        $again=1;

         //on aplatit le tableau
         $flatarray=$flatener->flatten($allcover);
    
         //Si le nom existe déjà on aloue un num random derrière le nom existant
        do{
         $numrandom=rand(0,9);//generation d'un num random
         $Filename_clean=substr($Filename_cover, 0, -5);// on enlève le .jpeg
         $Filename_cover=$Filename_clean.$numrandom.'.jpeg';//nom=nom+num+jpeg

         $counter=$counter+1;
             
         if(in_array($Filename_cover, $flatarray)){ //si le nom existe déjà dans la BDD
             $again=1;
         }else{
             $again=0;
         }
 
         }while($again==1);

         if($form->isSubmitted()&& $form->isValid()){

            $avis->setDate(new \DateTime());

            $coverFile = $form->get('cover')->getData();

            if ($coverFile) {
                try {
                    $coverFile->move(
                        $this->getParameter('cover_avis_directory'),
                        $Filename_cover
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $avis->setCover($Filename_cover);
            }

            $manager->persist($avis);
            $manager->flush();

            return $this->redirectToRoute('avis_jeu');
        }

    	return $this->render('avis_jeu/add.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    //Modifier un Article News
    /**
     * @Route("/admin/news/avis/{id}", name="edit_avis")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit($id, Request $request)
    {
        //Récupération du Article News
        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(AvisJeu::class)->find($id);
        $titre=$news->getTitre();
        $contenu=$news->getContenu();
        $cover=$news->getCover();

        $Filename_cover=$cover;

        //Création d'un Formulaire
        $form = $this->createForm(AvisJeuType::class, $news);
        $form->handleRequest($request);

        //Modification du Article News
        if($form->isSubmitted()&& $form->isValid()){
            $news->setTitre($form['titre']->getData());
            $news->setContenu($form['contenu']->getData());
            $cover=$form['cover']->getData();
            if($cover!=NULL){
                
                $coverFile = $form->get('cover')->getData();

                if ($coverFile) {
                try {
                    $coverFile->move(
                        $this->getParameter('cover_avis_directory'),
                        $Filename_cover
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $news->setCover($Filename_cover);
            }
            }
                
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($news);
            $manager->flush();

            return $this->redirectToRoute('avis_jeu');
        }

        return $this->render('avis_jeu/edit_avis.html.twig',[
            'id'=>$id,
            'contenu'=>$contenu,
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/admin/avis/remove/{id}", name="remove_avis_choix")
     * @IsGranted("ROLE_ADMIN")
     */

    public function choixRemoveAvis($id){

        return $this->render('avis_jeu/remove_avis.html.twig',[
            'id'=>$id
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="remove_avis")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeAvis($id)
    {
        //Récupération de l'Article
        $entityManager = $this->getDoctrine()->getManager();
        $avis = $entityManager->getRepository(AvisJeu::class)->find($id);

        //Suppression de l'Article
        $entityManager->remove($avis);
        $entityManager->flush();

        return $this->redirectToRoute('avis_jeu');
    }
}
