<?php

namespace tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testLinkToTasksList()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Consulter la liste des tâches à faire')->link();
        $client->click($link);
        $this->assertRouteSame('task_list');
        $this->assertResponseIsSuccessful();
    }

    public function testDisplaysAllTasks()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $tasksCount = count($taskRepository->findAll());

        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertCount($tasksCount, $crawler->filter('.thumbnail'));
    }

    public function testDeleteOneTask()
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

    public function testToggleOneTask()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler->selectButton('Marquer comme faite')->form();
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

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

        $link = $crawler->selectLink('Créer une tâche')->link();
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

        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
        
        // $this->assertSelectorTextContains('h4 > a', 'Test de titre');
        // does not work because only the first occurence is compared
        // reverse in twig ?
    }

    public function testSuccessfullTaskEdition()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $taskToEdit = $taskRepository->findOneBy([]);

        $crawler = $client->request(
            'GET', 
            '/tasks/' . $taskToEdit->getId() . '/edit'
        );
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Test de titre modifié',
            'task[content]' => 'Test de contenu modifié'
        ]);
        $client->click($form);

        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
