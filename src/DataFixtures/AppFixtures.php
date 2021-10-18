<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    protected $hasher;

    public function __construct(userPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
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

        $index = 1;

        for ($i = 1; $i <= 10; $i++) {
            $newUser = new User;
            $newUser->setUsername('user' . $i);
            $hashedPassword = $this->hasher->hashPassword($newUser, 'password');
            $newUser->setPassword($hashedPassword);
            $newUser->setEmail('user' . $i . '@gmail.com');
            $manager->persist($newUser);

            for ($j = 1; $j <= 3; $j++) {
                $newTask = new Task;
                $newTask->setTitle('Tâche n°' . $index . ' pour ' . $newUser->getUsername());
                $newTask->setContent($tasks[array_rand($tasks)]);
                $manager->persist($newTask);
                $index++;
            }
        }

        $manager->flush();
    }
}
