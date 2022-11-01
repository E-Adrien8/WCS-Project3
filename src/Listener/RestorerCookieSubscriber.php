<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\Restorer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class RestorerCookieSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'invalidateRestorerCookie',
        ];
    }

    public function invalidateRestorerCookie(LogoutEvent $event): void
    {
        if (!$event->getResponse()) {
            // R
            return;
        }

        if ($event->getToken()?->getUser() instanceof Restorer) {
            $event->getResponse()->headers->clearCookie('restorer');
        }
    }
}
