<?php

namespace App\Controller;

use App\Entity\User;
use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(UserRepository $repo,Request $request,PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = new SearchData();
        $data->page = $request->get('page',1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        $users = $repo->findQueryResult($data, $paginator);

        if($form->get('clear')->isClicked()){
            return $this->redirectToRoute('users');
        }

        // $users = $repo->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function show(User $user) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
