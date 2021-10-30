<?php

/*
 * This file is part of the ToDoList App
 * OpenClassRooms PHP/Symfony project 8
 * 
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findOneOfAnotherUser(User $user): Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.author != :user')
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
