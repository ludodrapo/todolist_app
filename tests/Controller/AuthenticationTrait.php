<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Trait AuthenticationTrait
 * @package App\Tests\Controller
 */
trait AuthenticationTrait
{
    /**
     * @return KernelBrowser
     */
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

    /**
     * @return KernelBrowser
     */
    public static function createAuthenticatedWithRoleAdminClient(): KernelBrowser
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
