<?php

namespace App\Handler;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CreateTaskHandler
 * @package App\Handler
 */
class CreateTaskHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param Security $security
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
        $task->setAuthor($this->security->getUser());
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
