<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Photos;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET","POST"})
     */
    public function index(PostRepository $postRepository, Request $request, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $post = new Post();


        $posts = $postRepository->findBy(array(), array('createdAt' =>'DESC'));

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $photos = $form->get('photos')->getData();

 
            // On boucle sur les photos
            foreach($photos as $photo){
                // On génère un nouveau nom de fichier
             

                $fichier = md5(uniqid()).'.'.$photo->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $photo->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée la photo dans la base de données
                $img = new Photos();
                $img->setName($fichier);
                $post->addPhoto($img);
            }


            $post->setCreatedAt(new \DateTime());
            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();

            
            $this->addFlash('success','Le post a bien été ajouté');

            return $this->redirectToRoute('post_index');
        }
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("post/newadd", name="post_new", methods={"GET", "POST"})
     */
    public function new(Request $request,  ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
    
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les photos transmises
            $photos = $form->get('photos')->getData();

            
            // On boucle sur les photos
            foreach($photos as $photo){
                // On génère un nouveau nom de fichier
             

                $fichier = md5(uniqid()).'.'.$photo->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $photo->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée la photo dans la base de données
                $img = new Photos();
                $img->setName($fichier);
                $post->addPhoto($img);
            }
        
            $post->setCreatedAt(new \DateTime());
            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();
        
            $this->addFlash('success','Le post a bien été ajouté');

            return $this->redirectToRoute('post_index');
        }
        dump($post);

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    /**
     * @Route("post/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("post/{id}/edit", name="post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les photos transmises
            $photos = $form->get('photos')->getData();
            
            // On boucle sur les photos
            foreach($photos as $photo){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$photo->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $photo->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée la photo dans la base de données
                $img = new Photos();
                $img->setName($fichier);
                $post->addPhoto($img);
            }
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        
            return $this->redirectToRoute('post_index');
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * @Route("post/{id}", name="post_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("post/supprime/image/{id}", name="post_delete_photos", methods={"DELETE"})
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

}
