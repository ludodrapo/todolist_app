<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method array<User> findAll()
 * @method array<User> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * To get one user based on the role he has
     * This method only needs 'admin' for now
     */
    public function findOneByRole(string $role): User
    {
        $role = '"ROLE_' . strtoupper($role) . '"';

        return $this->createQueryBuilder('q')
            ->andWhere('JSON_CONTAINS(q.roles, :roles) = 1')
            ->setParameter('roles', $role)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
