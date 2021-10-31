<?php

/**
 * This file is part of OpenClassRooms project 8 ToDoList
 * Modified by Ludovic Drapeau <ludodrapo@gmail.com>
 */

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class TaskAccessVoter extends Voter
{
    /**
     * @param Task $subject
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['CAN_EDIT'])
            && $subject instanceof \App\Entity\Task;
    }

    /**
     * @param Task $subject
     */
    protected function voteOnAttribute(
        string $attribute,
        $subject,
        TokenInterface $token
    ): bool {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (! $user instanceof UserInterface) {
            return false;
        }
        // CAN_EDIT case
        if (
            $subject->getAuthor() === $user ||
            (in_array('ROLE_ADMIN', $user->getRoles()) &&
                $subject->getAuthor() === null)
        ) {
            return true;
        }

        return false;
    }
}
