<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Handler\CreateUserHandler;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(UserRepository $userRepository): Response
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
    ): Response {
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
    ): Response {
        if ($userHandler->handle($request, $user)) {
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $userHandler->createView(),
                'user' => $user,
            ]
        );
    }
}
