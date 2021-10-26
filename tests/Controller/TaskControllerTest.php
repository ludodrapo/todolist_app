<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Tests\Controller\AuthenticationTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class TaskControllerTest
 * @package tests\Controller
 */
class TaskControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testLinkToTasksList()
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Liste des tâches')->link();
        $client->click($link);
        $this->assertRouteSame('task_list');
        $this->assertResponseIsSuccessful();
    }

    public function testDisplaysThisUserWithRoleUserTasks()
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $testUser */
        $testUser = $userRepository->findOneBy([]);

        $client->loginUser($testUser);

        $tasksCount = count($testUser->getTasks());

        // dd($client->getContainer()->get('session'));

        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertCount($tasksCount, $crawler->filter('.list-group-item'));
    }

    public function testDisplaysThisUserWithRoleAdminTasks()
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $adminUser */
        $adminUser = $userRepository->findOneByRole('admin');

        $client->loginUser($adminUser);

        /** @var TaskRepository $taskrepository */
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        /** @var Task $anonymousTask */
        $anonymousTasks = $taskRepository->findBy(['author' => null]);

        $tasksCount = count($adminUser->getTasks()) + count($anonymousTasks);

        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertCount($tasksCount, $crawler->filter('.list-group-item'));
    }

    public function testSuccessfullDeleteOneTask()
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler->selectButton('delete-btn')->form();
        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testSuccessfullToggleOneTask()
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler->selectButton('task-done-btn')->form();
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('task-undone-btn')->form();
        $client->submit($form);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testLinkToTaskCreationPage()
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks');

        $link = $crawler->selectLink('Créer une nouvelle tâche')->link();
        $client->click($link);
        $this->assertRouteSame('task_create');
        $this->assertResponseIsSuccessful();
    }

    public function testSuccessfullTaskCreation()
    {
        $client = static::createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test de titre',
            'task[content]' => 'Test de contenu'
        ]);

        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testSuccessfullTaskEdition()
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $testUser */
        $testUser = $userRepository->findOneBy([]);

        $client->loginUser($testUser);

        $userTasks = $testUser->getTasks();

        $crawler = $client->request(
            'GET',
            '/tasks/' . $userTasks[0]->getId() . '/edit'
        );

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Test de titre modifié',
            'task[content]' => 'Test de contenu modifié'
        ]);

        $client->click($form);
        $client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testFailureOnTryingToEditTaskOfAnotherUser()
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        /** @var TaskRepository $taskrepository */
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        /** @var Task $task */
        $anotherUsersTask = $taskRepository->findOneOfAnotherUser($testUser);

        $client->CatchExceptions(false);

        $client->request(
            'GET',
            '/tasks/' . $anotherUsersTask->getId() . '/edit'
        );
    }

    public function testFailureOnTryingToToggleTaskOfAnotherUser()
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        /** @var TaskRepository $taskrepository */
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        /** @var Task $task */
        $anotherUsersTask = $taskRepository->findOneOfAnotherUser($testUser);

        $client->CatchExceptions(false);

        $client->request(
            'GET',
            '/tasks/' . $anotherUsersTask->getId() . '/toggle'
        );
    }

    public function testFailureOnTryingToDeleteTaskOfAnotherUser()
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        /** @var TaskRepository $taskrepository */
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        /** @var Task $task */
        $anotherUsersTask = $taskRepository->findOneOfAnotherUser($testUser);

        $client->CatchExceptions(false);

        $client->request(
            'GET',
            '/tasks/' . $anotherUsersTask->getId() . '/delete'
        );
    }
}
