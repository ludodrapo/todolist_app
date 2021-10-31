<?php

namespace App\Handler;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EditTaskHandler
 * @package App\Handler
 */
class EditTaskHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EditTaskHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $this->entityManager->flush();
    }
}
