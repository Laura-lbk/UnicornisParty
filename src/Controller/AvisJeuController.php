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
}
