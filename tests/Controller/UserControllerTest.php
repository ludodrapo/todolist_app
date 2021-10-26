<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Controller\AuthenticationTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserControllerTest
 * @package tests\Controller
 */
class UserControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testLinkToUserCreationPageWithRoleAdminReturnsOk()
    {
        $client = static::createAuthenticatedWithRoleAdminClient();

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Créer un utilisateur')->link();

        $client->click($link);

        $this->assertRouteSame('user_create');
        $this->assertResponseIsSuccessful();
    }

    public function testNoLinkToUsersPathWithRoleUser()
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextNotContains('a', 'Créer un utilisateur');
    }

    public function testUsersPathAccessDeniedToRoleUser()
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createAuthenticatedClient();

        $client->CatchExceptions(false);

        $client->request('GET', '/users');
    }

    public function testSuccessfullUserCreation()
    {
        $client = static::createAuthenticatedWithRoleAdminClient();

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
        $client = static::createAuthenticatedWithRoleAdminClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $userToEdit */
        $userToEdit = $userRepository->findOneBy([]);

        $crawler = $client->request(
            'GET',
            '/users/' . $userToEdit->getId() . '/edit'
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
