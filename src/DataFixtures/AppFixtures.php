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
            'Acheter du pain',
            'Déposer costumes chez le teinturier',
            'Faire double des clés',
            'Faire les courses',
            'Réserver les vacances',
            'Appeler mamie',
            'Faire les vitres',
            'Finir le projet 8',
            'Postuler chez OpenClassRooms',
            'Faire le ménage dans les papiers',
            'Classer les petites pièces de Lego'
        ];

        $details = [
            "Penser à bien s'organiser avant et prévoir tout ce qu'il faut pour ne pas se retrouver le bec dans l'eau.",
            "Bien dire à tout le monde qu'on va le faire bientôt, histoire de s'engager à la réalisation de cette tâche.",
            "Prendre son temps pour la réalisation pour ne pas faire d'erreur et ne pas avoir à tout refaire.",
            "Surtout ne rien dire à personne pour, en cas d'échec cuisant,
            être encore considéré en tant que tel et ne pas être la victime de quolifichets !"
        ];

        for ($i = 1; $i <= 10; $i++) {
            $newTask = new Task();
            $newTask->setTitle($tasks[array_rand($tasks)]);
            $newTask->setContent($details[array_rand($details)]);
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
                $newTask->setTitle($tasks[array_rand($tasks)]);
                $newTask->setContent($details[array_rand($details)]);
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
