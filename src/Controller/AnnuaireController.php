<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnuaireController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Post::class);

        $posts = $repo->findAll();

        return $this->render('annuaire/index.html.twig', [
            'controller_name' => 'AnnuaireController',
            'posts' => $posts
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('annuaire/home.html.twig');
    }
}
