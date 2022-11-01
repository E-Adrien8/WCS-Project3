<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventReservationVoter extends Voter
{
    public const RESERVATION = 'event.reservation';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::RESERVATION && $subject instanceof Event;
    }

    /**
     * @param Event $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return $subject->getDate()->getTimestamp() >= time();
    }
}
