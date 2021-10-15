<?php

namespace tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testDisplaysLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('login');
    }

    public function testLoginCheckWithUnknownUsername()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'unknown',
            '_password' => 'password'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginCheckWithBadPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user1',
            '_password' => 'wrongPassword'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user1',
            '_password' => 'password'
        ]);

        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Bienvenue');
    }

    public function testSuccessfullLogout()
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Se déconnecter')->link();

        $client->click($link);
        $this->assertRouteSame('logout');
        $this->assertResponseStatusCodeSame(302);
    }
}