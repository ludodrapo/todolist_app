<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractHandler
 *
 * @package App\Handler
 */
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * Function name setFormFactory not to be changed
     *
     * @required
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    public function handle(
        Request $request,
        object $data,
        array $options = []
    ): bool {
        $this->form = $this->formFactory->create(
            $this->getFormType(),
            $data,
            $options
        )->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->process($data);
            return true;
        }

        return false;
    }

    public function createView(): FormView
    {
        return $this->form->createView();
    }

    abstract protected function getFormType(): string;

    abstract protected function process(object $data): void;
}
