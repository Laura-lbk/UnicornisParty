<?php

//Controller de l'inscription

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;

use App\Form\RegistrationUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="user_security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user= new User;

        $form = $this->createForm(RegistrationUserFormType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('user_security/registration.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
