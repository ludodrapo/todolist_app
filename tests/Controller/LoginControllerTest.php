<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LoginControllerTest
 *
 * @package tests\Controller
 */
class LoginControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testDisplaysLoginPage(): void
    {
        /** @var KernelBrowser $client */
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('login');
    }

    public function testLoginCheckWithUnknownUsername(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'unknown',
            '_password' => 'password',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginCheckWithBadPassword(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user1',
            '_password' => 'wrongPassword',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user1',
            '_password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Bienvenue');
    }

    public function testSuccessfullLogout(): void
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Se déconnecter')->link();

        $client->click($link);

        $this->assertRouteSame('logout');
        $this->assertResponseStatusCodeSame(302);
    }
}
