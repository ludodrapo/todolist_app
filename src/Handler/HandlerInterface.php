<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Handler;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface HandlerInterface
 *
 * @package App\Handler
 */
interface HandlerInterface
{
    /**
     * @param array<string,mixed> $options
     */
    public function handle(
        Request $request,
        object $data,
        array $options = []
    ): bool;

    public function createView(): FormView;
}
