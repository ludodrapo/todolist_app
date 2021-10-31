<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CreateTaskHandler
 *
 * @package App\Handler
 */
final class CreateTaskHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var Security $security
     */
    private $security;

    /**
     * TaskHandler constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    protected function getFormType(): string
    {
        return TaskType::class;
    }

    /**
     * @param Task $task
     */
    protected function process($task): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $task->setAuthor($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
