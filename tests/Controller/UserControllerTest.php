<?php

namespace tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLinkToUserCreationPageWithRoleAdminReturnsOk()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['roles' => ["ROLE_ADMIN"]]);
        dd($testUser);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Créer un utilisateur')->link();

        $client->click($link);

        $this->assertRouteSame('user_create');
        $this->assertResponseIsSuccessful();
    }

    public function testNoLinkToUsersPathWithRoleUser()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([
            'roles' => []
        ]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $this->assertSelectorTextNotContains('a', 'Créer un utilisateur');
    }

    public function testUsersPathAccessDeniedToRoleUser()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([
            'roles' => ['ROLE_USER']
        ]);
        $client->loginUser($testUser);

        $client->request('GET', '/users');
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullUserCreation()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy([
            'roles' => ['ROLE_ADMIN']
        ]);
        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'testUser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'testUser@gmail.com',
            'user[roles]' => ['ROLE_USER']
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
        $testUser = $userRepository->findOneBy([
            'roles' => ['ROLE_USER']
        ]);
        $adminUser = $userRepository->findOneBy([
            'roles' => ['ROLE_ADMIN']
        ]);
        $client->loginUser($adminUser);

        $crawler = $client->request(
            'GET', 
            '/users/' . $testUser->getId() . '/edit'
        );
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'modifiedTestUser',
            'user[password][first]' => 'newPassword',
            'user[password][second]' => 'newPassword',
            'user[email]' => 'modifedTestUser@gmail.com',
            'user[roles]' => ['ROLE_ADMIN']
        ]);
        $client->click($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
