<?php

namespace App\Controller\Account\Dto;

use App\Entity\User;
use App\Validator\UniqueUsername;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email;

    /**
     * @Assert\NotBlank
     */
    public string $password;

    /**
     * @Assert\NotBlank
     */
    public string $avatar;

    /**
     * @Assert\NotBlank
     * @UniqueUsername
     */
    public string $username;

    public function toEntity(): User
    {
        return (new User)
            ->setEmail($this->email)
            ->setPassword($this->password)
            ->setUsername($this->username)
            ->setAvatar($this->avatar);
    }
}
