<?php


namespace App\Services\Internal;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    /**
     * Creates instance of @see ValidatorInterface
     *
     * @return ValidatorInterface
     */
    private function buildValidator(): ValidatorInterface
    {
        $validator = Validation::createValidator();
        return $validator;
    }

    /**
     * todo: only prepared base logic, the violations need to be extracted later on
     *  this method is not ready to be used
     *
     * Validates the object and returns the array of violations
     *
     * @param object $object
     * @return string[]
     */
    public function validateAndReturnArrayOfInvalidFieldsWithMessages(object $object): array
    {
        $validator  = $this->buildValidator();
        $violations = $validator->validate($object);

        return [];
    }

    /**
     * @param object $object
     * @return string
     */
    public function validateAndReturnInvalidFieldsWithMessagesForResponse(object $object): string
    {
        // todo:

        return "";
    }



}