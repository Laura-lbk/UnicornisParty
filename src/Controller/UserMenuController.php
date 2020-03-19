<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Form\UserMenuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            'email'=>$email,
            'iduser'=>$id
        ]);
    }

    public function passwordChange($id, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){

        $form = $this->createForm(PasswordType::class);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        var_dump($user);
        
        if($form->isSubmitted()&& $form->isValid()){
            
            $old_pwd = $form->get('oldpassword')->getData(); 
            $new_pwd = $form->get('newpassword')->getData(); 
            $checkPass = $encoder->isPasswordValid($user, $old_pwd);

            if($checkPass === true) {
                $new_pwd_encoded = $encoder->encodePassword($user, $new_pwd); 
                $user->setPassword($new_pwd_encoded);
                $manager->persist($user);
                $manager->flush();
                    
            } else {
              return new jsonresponse(array('error' => 'Mauvais Mot de Passe.'));
            }
          }
        
        return $this->render('user/user_password.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
