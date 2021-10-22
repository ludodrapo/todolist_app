<?php

namespace tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskControllerTest extends WebTestCase
{
    public function testLinkToTasksList()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Liste des tâches')->link();
        $client->click($link);
        $this->assertRouteSame('task_list');
        $this->assertResponseIsSuccessful();
    }

    public function testDisplaysThisUserWithRoleUserTasks()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $tasksCount = count($testUser->getTasks());

        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertCount($tasksCount, $crawler->filter('.thumbnail'));
    }

    public function testDisplaysThisUserWithRoleAdminTasks()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByRole('admin');
        $client->loginUser($adminUser);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $anonymousTasks = $taskRepository->findBy(['author' => null]);

        $adminTasksCount = count($adminUser->getTasks());
        $anonymousTasksCount = count($anonymousTasks);
        $tasksCount = $adminTasksCount + $anonymousTasksCount;

        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertCount($tasksCount, $crawler->filter('.thumbnail'));
    }

    public function testSuccessfullDeleteOneTask()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler->selectButton('Supprimer')->form();
        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testSuccessfullToggleOneTask()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler->selectButton('Marquer comme faite')->form();
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Marquer non terminée')->form();
        $client->submit($form);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testLinkToTaskCreationPage()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks');

        $link = $crawler->selectLink('Créer une nouvelle tâche')->link();
        $client->click($link);
        $this->assertRouteSame('task_create');
        $this->assertResponseIsSuccessful();
    }

    public function testSuccessfullTaskCreation()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

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

        $userRepository = static::getContainer()->get(UserRepository::class);
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

        $taskRepository = $this->getContainer()->get(TaskRepository::class);
        $anonymousTask = $taskRepository->findOneBy(['author' => null]);

        $client->CatchExceptions(false);
        $client->request('GET', '/tasks/' . $anonymousTask->getId() . '/edit');
    }

    public function testFailureOnTryingToToggleTaskOfAnotherUser()
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $taskRepository = $this->getContainer()->get(TaskRepository::class);
        $anonymousTask = $taskRepository->findOneBy(['author' => null]);

        $client->CatchExceptions(false);
        $client->request('GET', '/tasks/' . $anonymousTask->getId() . '/toggle');
    }

    public function testFailureOnTryingToDeleteTaskOfAnotherUser()
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $taskRepository = $this->getContainer()->get(TaskRepository::class);
        $anonymousTask = $taskRepository->findOneBy(['author' => null]);

        $client->CatchExceptions(false);
        $client->request('GET', '/tasks/' . $anonymousTask->getId() . '/delete');
    }
}
