<?php

namespace App\Controller;

use App\Entity\Coloriage;
use App\Form\ColoriageType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index()
    {
        return $this->render('coloriage/index.html.twig', [
            'controller_name' => 'ColoriageController',
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

        if($form->isSubmitted()&& $form->isValid()){

            $pathFile = $form->get('path')->getData();

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename_image = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename_img = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $originalFilename_image ) );
                $newFilename_img = $safeFilename_img.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_coloriage_directory'),
                        $newFilename_img
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $coloriage->setImage($newFilename_img);
            }

            if ($pathFile) {
                $originalFilename_pdf = pathinfo($pathFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $originalFilename_pdf ) );
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pathFile->guessExtension();

                try {
                    $pathFile->move(
                        $this->getParameter('pdf_coloriage_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $coloriage->setPath($newFilename);
            }

            $manager->persist($coloriage);
            $manager->flush();

            return $this->redirectToRoute('coloriage');
        }

    	return $this->render('coloriage/add_coloriage.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
