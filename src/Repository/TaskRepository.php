<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method array<Task> findAll()
 * @method array<Task> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findOneOfAnotherUser(User $user): Task
    {
        /** @var Task $task */
        $task = $this->createQueryBuilder('t')
            ->andWhere('t.author != :user')
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        return $task;
    }
}
