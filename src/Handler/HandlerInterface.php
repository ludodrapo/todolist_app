<?php

namespace App\Handler;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface HandlerInterface
 * @package App\\Handler
 */
interface HandlerInterface
{
    /**
     * @param Request $request
     * @param object $data
     * @param array $options
     * @return bool
     */
    public function handle(Request $request, object $data, array $options = []): bool;

    /**
     * @return FormView
     */
    public function createView(): FormView;
}
