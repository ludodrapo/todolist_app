<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EditTaskHandler
 *
 * @package App\Handler
 */
final class EditTaskHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EditTaskHandler constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $this->entityManager->flush();
    }
}
