<?php

namespace App\Controller;

use App\Entity\Coloriage;
use App\Form\ColoriageType;
use App\Service\Flatener;
use App\Repository\ColoriageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColoriageController extends AbstractController
{
    /**
     * @Route("/coloriage", name="coloriage")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Coloriage::class);

        $coloriages=$repo->findAll();

        $coloriagespage=$paginator->paginate(
            $coloriages,
            $request->query->getInt('page',1),
            6 //max de articles par page

    );

        return $this->render('coloriage/index.html.twig', [
            'coloriagespage'=>$coloriagespage
        ]);
    }

    /**
     * @Route("/admin/coloriage/add", name="add_coloriage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addColoriage(Request $request, EntityManagerInterface $manager, Flatener $flatener)
    { 
        $coloriage= new Coloriage();
        $form = $this->createForm(ColoriageType::class, $coloriage);
        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository(Coloriage::class);
        $allfanart=$repo->findImage();

        $Filename_img="image.jpeg";
        $Filename_pdf='';
        $again=1;
        $numcompose='';
    
            //on aplatit le tableau
        $flatarray=$flatener->flatten($allfanart);
    
            //Si le nom existe déjà on aloue un num random derrière le nom existant
        do{
            $numrandom=rand(0,9);//generation d'un num random
            $Filename_clean=substr($Filename_img, 0, -5);// on enlève le .jpeg
            $Filename_img=$Filename_clean.$numrandom.'.jpeg';//nom=nom+num+jpeg
            $numcompose=$numcompose.$numrandom;
                
            if(in_array($Filename_img, $flatarray)){ //si le nom existe déjà dans la BDD
                $again=1;
            }else{
                $again=0;
                $Filename_pdf='pdf'.$numcompose.'.pdf';
            }
    
            }while($again==1);

        if($form->isSubmitted()&& $form->isValid()){

            $pathFile = $form->get('path')->getData();
            $imageFile = $form->get('image')->getData();
            $name = $form->get('nom')->getData();
            $safe_name = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $name) );
            
            if ($imageFile) {
                try {
                    $imageFile->move(
                        $this->getParameter('image_coloriage_directory'),
                        $Filename_img
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $coloriage->setImage($Filename_img);
            }

            if ($pathFile) {
                try {
                    $pathFile->move(
                        $this->getParameter('pdf_coloriage_directory'),
                        $Filename_pdf
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $coloriage->setPath($Filename_pdf);
            }

            $coloriage->setPdfname($safe_name);

            $manager->persist($coloriage);
            $manager->flush();

            return $this->redirectToRoute('coloriage');
        }

    	return $this->render('coloriage/add_coloriage.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
}
