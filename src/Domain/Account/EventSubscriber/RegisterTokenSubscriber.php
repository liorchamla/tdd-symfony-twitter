<?php

namespace App\Domain\Account\EventSubscriber;

use App\Domain\Account\Event\RegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Uid\Uuid;

class RegisterTokenSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'generateTokenForUser'
        ];
    }

    public function generateTokenForUser(RegisterEvent $e)
    {
        $user = $e->getUser();

        if ($user->getConfirmationToken() !== null) {
            return;
        }

        $user->setConfirmationToken(Uuid::v1());
    }
}
