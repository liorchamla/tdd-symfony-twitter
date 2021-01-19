<?php

namespace App\Domain\Account\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUsernameValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($username, Constraint $constraint)
    {
        if (!$username) {
            return;
        }

        $count = $this->userRepository->count([
            'username' => $username
        ]);

        if ($count > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $username)
                ->addViolation();
        }
    }
}
