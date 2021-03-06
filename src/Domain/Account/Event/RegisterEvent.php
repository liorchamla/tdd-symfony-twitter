<?php

namespace App\Domain\Account\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RegisterEvent extends Event
{
    public const NAME = "account.register";

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
