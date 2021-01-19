<?php

namespace App\EventSubscriber;

use App\Event\RegisterEvent;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV1Generator;
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
