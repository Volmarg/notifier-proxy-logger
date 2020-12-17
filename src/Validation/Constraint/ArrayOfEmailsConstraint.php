<?php

namespace App\Validation\Constraint;

use App\Validation\Validator\ArrayOfEmailsValidator;
use Symfony\Component\Validator\Constraint;

class ArrayOfEmailsConstraint extends Constraint
{
    public string $message = "At least one of the values in array is not valid E-mail";

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ArrayOfEmailsValidator::class;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}