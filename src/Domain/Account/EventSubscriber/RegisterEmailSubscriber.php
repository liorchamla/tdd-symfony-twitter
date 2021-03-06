<?php

namespace App\Domain\Account\EventSubscriber;

use App\Domain\Account\Event\RegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegisterEmailSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'sendEmailToUser'
        ];
    }

    public function sendEmailToUser(RegisterEvent $e)
    {
        $email = new Email();
        $email->to($e->getUser()->getEmail())
            ->subject("Confirmez votre adresse email !")
            ->html("Merci d'activer votre compte en cliquant sur le lien suivant : <a href=''></a>")
            ->from("contact@my.com");
        $this->mailer->send($email);
    }
}
