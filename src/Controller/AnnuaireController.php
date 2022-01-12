<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnuaireController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index(PostRepository $repo,Request $request, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $posts = $repo->findBy(array(), array('createdAt' =>'DESC'));

        $user = $this->getUser();
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post->setCreatedAt(new \DateTime());

            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();

            
            $this->addFlash('success','Le post a bien été ajouté');

            return $this->redirectToRoute('posts');
        }

        
        return $this->render('annuaire/index.html.twig', [
            'controller_name' => 'AnnuaireController',
            'posts' => $posts,
            'formPost' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/new", name="post_create")
     * @Route("post/{id}/edit", name="post_edit")
     */
    public function formPost(Post $post = null,Request $request, ObjectManager $manager) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        if(!$post){
            $post = new Post();
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$post->getId()){
                $post->setCreatedAt(new \DateTime());
            }

            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();

            
            $this->addFlash('success','Le post a bien été ajouté');

            return $this->redirectToRoute('posts');
        }

        dump($post);

        return $this->render('annuaire/create.html.twig', [
            'formPost' => $form->createView(),
            'editMode' => $post->getId() !== null
        ]);
    }    

}
