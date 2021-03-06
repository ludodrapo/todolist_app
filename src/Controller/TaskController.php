<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Handler\CreateTaskHandler;
use App\Handler\EditTaskHandler;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(TaskRepository $taskRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $tasks = $taskRepository->findBy(['author' => $user]);

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $anonymousTasks = $taskRepository->findBy(['author' => null]);
            foreach ($anonymousTasks as $anonymousTask) {
                $tasks[] = $anonymousTask;
            }
        }

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(
        Request $request,
        CreateTaskHandler $taskHandler
    ): Response {
        $task = new Task();

        if ($taskHandler->handle($request, $task)) {
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/create.html.twig',
            [
                'form' => $taskHandler->createView(),
            ]
        );
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(
        Task $task,
        Request $request,
        EditTaskHandler $taskHandler
    ): Response {
        $this->denyAccessUnlessGranted(
            'CAN_EDIT',
            $task,
            "Vous n'êtes pas autorisé(e) à modifier à cette ressource."
        );

        if ($taskHandler->handle($request, $task)) {
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $taskHandler->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(
        Task $task,
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        $this->denyAccessUnlessGranted(
            'CAN_EDIT',
            $task,
            "Vous n'êtes pas autorisé(e) à modifier cette ressource."
        );

        $task->toggle(! $task->isDone());
        $entityManager->flush();

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(
        Task $task,
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        $this->denyAccessUnlessGranted(
            'CAN_EDIT',
            $task,
            "Vous n'êtes pas autorisé(e) à effacer cette ressource."
        );

        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
