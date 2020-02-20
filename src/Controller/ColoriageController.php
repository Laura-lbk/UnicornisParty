<?php

namespace App\Controller;

use App\Entity\Coloriage;
use App\Form\ColoriageType;
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
    public function addColoriage(Request $request, EntityManagerInterface $manager)
    { 
        $coloriage= new Coloriage();
        $form = $this->createForm(ColoriageType::class, $coloriage);
        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository(Coloriage::class);

        $allcoloriages=$repo->findall();

        if($allcoloriages){
            $nbcoloriage=count($allcoloriages);
        }
        else{
            $nbcoloriage=0;
        }

        $id= ($nbcoloriage+1);


        if($form->isSubmitted()&& $form->isValid()){

            $pathFile = $form->get('path')->getData();

            $imageFile = $form->get('image')->getData();

            $Filename_img="image$id.jpeg";
            $Filename_pdf="coloriage$id.pdf";


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

            $manager->persist($coloriage);
            $manager->flush();

            return $this->redirectToRoute('coloriage');
        }

    	return $this->render('coloriage/add_coloriage.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
}
