<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_registration")
     * @Route("/user/{id}/edit", name="security_user_edit")
     */
    public function form(User $user = null, Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){
        
        if(!$user){
            $user = new User();
        }
        else{
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        }

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'editMode' => $user->getId() !== null
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login() {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("logout", name="security_logout")
     */
    public function logout() {

    }
}
