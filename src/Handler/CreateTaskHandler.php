<?php

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
class CreateTaskHandler extends AbstractHandler
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
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    protected function getFormType(): string
    {
        return TaskType::class;
    }

    /**
     * @inheritDoc
     */
    protected function process($task): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        /** @var Task $task */
        $task->setAuthor($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
