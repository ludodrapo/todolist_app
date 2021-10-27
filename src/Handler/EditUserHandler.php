<?php

namespace App\Handler;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class EditUserHandler
 * @package App\Handler
 */
class EditUserHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    /**
     * TaskHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher
    ) {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }

    /**
     * @inheritDoc
     */
    protected function getFormType(): string
    {
        return UserType::class;
    }

    /**
     * @inheritDoc
     */
    protected function process($user): void
    {
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPassword()
            )
        );

        $this->entityManager->flush();
    }
}
