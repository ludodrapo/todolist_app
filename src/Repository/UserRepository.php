<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * To get one user based on the role he has
     * This method only needs 'admin' or 'user'
     * @param string $role
     * @return void
     */
    public function findOneByRole(string $role)
    {
        $role = '"ROLE_' . strtoupper($role) . '"';

        $result = $this->createQueryBuilder('q')
            ->andWhere('JSON_CONTAINS(q.roles, :roles) = 1')
            ->setParameter('roles', $role)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $result[0];
    }
}
