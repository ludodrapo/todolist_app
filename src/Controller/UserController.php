<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\CreateUserHandler;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(UserRepository $userRepository)
    {
        return $this->render(
            'user/list.html.twig',
            ['users' => $userRepository->findAll()]
        );
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(
        Request $request,
        CreateUserHandler $userHandler
    ) {
        $user = new User();

        if ($userHandler->handle($request, $user)) {

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/create.html.twig',
            ['form' => $userHandler->createView()]
        );
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(
        User $user,
        Request $request,
        CreateUserHandler $userHandler
    ) {

        if ($userHandler->handle($request, $user)) {

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $userHandler->createView(),
                'user' => $user
            ]
        );
    }
}
