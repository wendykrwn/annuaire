<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Photos;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_registration")
     * @Route("/user/edit", name="security_user_edit")
     */
    public function form(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){
        
        $user = $this->getUser();
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

            $photo = $form->get('userProfile')->getData();
            if($photo){
                $fichier = md5(uniqid()).'.'.$photo->guessExtension();
                
                $photo->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                    
                $img = new Photos();
                $img->setName($fichier);
                $user->setUserProfile($img);
            }
           

            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Votre compte a bien été enregistré');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'editMode' => $user->getId() !== null
        ]);
    }

    /**
     * @Route("user/delete/photo/{id}", name="annonces_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Photos $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }


    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'error' => $error
        ]);
    }

    /**
     * @Route("logout", name="security_logout")
     */
    public function logout() {

    }
}
