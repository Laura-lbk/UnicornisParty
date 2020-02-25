<?php

namespace App\Controller;

use App\Entity\Fanart;
use App\Form\FanArtType;
use App\Service\Flatener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FanartController extends AbstractController
{
    /**
     * @Route("/wall_of_fame", name="wall_of_fame")
     */
    public function index(Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Fanart::class);

        $fanart=$repo->findAll(
            array("date"=>'DESC'));


        return $this->render('coloriage/wall_of_fame.html.twig', [
            'fanart'=>$fanart
        ]);
    }

    /**
     * @Route("/admin/fanart/add", name="add_fanart")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addFanart(Request $request, EntityManagerInterface $manager, Flatener $flatener)
    { 
        $fanart = new Fanart();
        $form = $this->createForm(FanArtType::class, $fanart);
        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository(Fanart::class);
        $allfanart=$repo->findImage();

        //On renomme le fichier
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

            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                try {
                    $imageFile->move(
                        $this->getParameter('image_fanart_directory'),
                        $Filename_img
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $fanart->setImage($Filename_img);
                $fanart->setDate(new \DateTime());
            }

            $manager->persist($fanart);
            $manager->flush();

            return $this->redirectToRoute('wall_of_fame');
        }

    	return $this->render('coloriage/add_fanart.html.twig',[
            'form'=>$form->createView(),
            'counter'=>$counter,
        ]);
    }
}
