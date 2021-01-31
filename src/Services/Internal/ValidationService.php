<?php


namespace App\Services\Internal;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    /**
     * @var ValidatorInterface $validator
     */
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates the object and returns the array of violations
     *
     * @param object $object
     * @return string[]
     */
    public function validateAndReturnArrayOfInvalidFieldsWithMessages(object $object): array
    {
        $violations             = $this->validator->validate($object);
        $violationsWithMessages = [];

        /**@var $constraintViolation ConstraintViolation*/
        foreach($violations as $constraintViolation){
            $violationsWithMessages[$constraintViolation->getPropertyPath()] = $constraintViolation->getMessage();
        }

        return $violationsWithMessages;
    }
}