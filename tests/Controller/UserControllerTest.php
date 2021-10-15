<?php

namespace tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLinkToUserCreatePage()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Créer un utilisateur')->link();

        $client->click($link);

        $this->assertRouteSame('user_create');
        $this->assertResponseIsSuccessful();
    }

    public function testSuccessfullUserCreation()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'testUser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'testUser@gmail.com'
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testSuccessfullUserEdition()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request(
            'GET', 
            '/users/' . $testUser->getId() . '/edit'
        );
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'modifiedTestUser',
            'user[password][first]' => 'newPassword',
            'user[password][second]' => 'newPassword',
            'user[email]' => 'modifedTestUser@gmail.com'
        ]);
        $client->click($form);

        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
