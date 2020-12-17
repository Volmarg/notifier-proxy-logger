<?php


namespace App\Validation\Validator;


use http\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ArrayOfEmailsValidator
 * @package App\Validation\Validator
 */
class ArrayOfEmailsValidator extends ConstraintValidator
{
    /**
     * Validate the array, check if all elements are emails
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if( !is_array($value) ){
            throw new UnexpectedValueException("Expected array, got: " . gettype($value) );
        }

        foreach( $value as $arrayItem ){
            if( !filter_var($arrayItem, FILTER_VALIDATE_EMAIL) ){
                $this->context->buildViolation($constraint->getMessage())
                    ->addViolation();
                return;
            }
        }

    }
}