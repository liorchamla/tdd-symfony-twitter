<?php

namespace App\Domain\Account\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginDto
{

    /**
     * @Assert\NotBlank
     */
    public string $usernameOrEmail;

    /**
     * @Assert\NotBlank
     */
    public string $plainPassword;
}
