<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Trait AuthenticationTrait
 *
 * @package App\Tests\Controller
 */
trait AuthenticationTrait
{
    public static function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneBy([]);

        $client->loginUser($user);

        return $client;
    }

    public static function createAuthenticatedAdminClient(): KernelBrowser
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneByRole('admin');

        $client->loginUser($user);

        return $client;
    }
}
