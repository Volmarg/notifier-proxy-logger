<?php

namespace App\Controller\Utils;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Utils extends AbstractController {

    const TRUE_AS_STRING  = "true";
    const FALSE_AS_STRING = "false";

    /**
     * @param string|bool $value
     * @return bool
     * @throws Exception
     */
    public static function getBoolRepresentationOfBoolString($value): bool
    {
        if( is_bool($value) ){
            return $value;
        }elseif( is_string($value) ){

            $allowedValues = [
                self::TRUE_AS_STRING, self::FALSE_AS_STRING
            ];

            if( !in_array($value, $allowedValues) ){
                throw new Exception("Not a bool string");
            }

            return self::TRUE_AS_STRING === $value;
        }else{
            throw new \TypeError("Not allowed type: " . gettype($value) );
        }

    }

}
