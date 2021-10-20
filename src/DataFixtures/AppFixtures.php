<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    protected $hasher;

    /**
     * AppFixtures constructo
     *
     * @param userPasswordHasherInterface $hasher
     */
    public function __construct(userPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Loading all needed data to test the application
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $tasks = [
            'acheter du pain',
            'déposer costumes chez le teinturier',
            'faire double des clés',
            'faire les courses',
            'réserver les vacances',
            'appeler mamie',
            'faire les vitres',
            'finir le projet 8',
            'postuler chez OpenClassRooms',
            'faire le ménage dans les papiers',
            'classer les petites pièces de Lego'
        ];

        for ($i = 1; $i <= 10; $i++) {
            $newTask = new Task();
            $newTask->setTitle('Tâche n°' . $i);
            $newTask->setContent($tasks[array_rand($tasks)]);
            $manager->persist($newTask);
        }

        $index = 11;

        for ($i = 1; $i <= 10; $i++) {
            $newUser = new User();
            $newUser->setUsername('user' . $i);
            $hashedPassword = $this->hasher->hashPassword($newUser, 'password');
            $newUser->setPassword($hashedPassword);
            $newUser->setEmail('user' . $i . '@gmail.com');
            $manager->persist($newUser);

            for ($j = 1; $j <= 3; $j++) {
                $newTask = new Task();
                $newTask->setTitle('Tâche n°' . $index . ' pour ' . $newUser->getUsername());
                $newTask->setContent($tasks[array_rand($tasks)]);
                $newTask->setAuthor($newUser);
                $manager->persist($newTask);
                $index++;
            }
        }

        $adminUser = new User();
        $hashedPassword = $this->hasher->hashPassword($adminUser, 'password');
        $adminUser->setUsername('admin');
        $adminUser->setPassword($hashedPassword);
        $adminUser->setEmail('admin@gmail.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);

        $manager->flush();
    }
}
