<?php

namespace App\Domain\Account\EventSubscriber;

use App\Domain\Account\Event\RegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterPasswordSubscriber implements EventSubscriberInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'encodeUserPassword'
        ];
    }

    public function encodeUserPassword(RegisterEvent $e)
    {
        $user = $e->getUser();

        $hash = $this->encoder->encodePassword($user, $user->getPassword());

        $user->setPassword($hash);
    }
}
