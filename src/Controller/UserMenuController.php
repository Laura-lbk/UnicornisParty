<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserMenuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserMenuController extends AbstractController
{
    /**
     * @Route("/MonProfil/{id}", name="profil")
     */
    public function index($id, Request $request, EntityManagerInterface $manager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        $email=$user->getEmail();
        
        $form = $this->createForm(UserMenuType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            $newemail = $form->get('email')->getData();
            $newnewsletter = $form->get('newsletter')->getData();
            $user->setEmail($newemail);
            $user->setNewsletter($newnewsletter);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profil', ['id'=>$id]);
        };


        return $this->render('user/user_menu.html.twig', [
            'controller_name' => 'UserMenuController',
            'form'=>$form->createView(),
            'email'=>$email
        ]);
    }
}
