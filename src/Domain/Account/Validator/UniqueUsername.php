<?php

namespace App\Domain\Account\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUsername extends Constraint
{
    public $message = "A user with the username {{ username }} already exists, choose an other one";
}
