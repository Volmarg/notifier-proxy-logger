<?php


namespace App\Services\Internal;


use App\Controller\Application;
use Exception;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;

class AttributeReaderService
{

    const CLASS_AND_METHOD_SEPARATOR            = "::";
    const COUNT_OF_ELEMENTS_IN_CLASS_AND_METHOD = 2;
    const METHOD_REGEX                          = "[a-zA-Z]";

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Will check if given class has given Attribute defined
     *
     * @param string $classNamespaceWithMethod
     * @param string $searchedAttributeNamespace
     * @return bool
     * @throws ReflectionException
     * @throws Exception
     */
    public function hasMethodAttribute(string $classNamespaceWithMethod, string $searchedAttributeNamespace): bool
    {
        if( !$this->isValidClassWithMethod($classNamespaceWithMethod) ){
            throw new Exception("Invalid classNamespaceWithMethod has been provided: {$classNamespaceWithMethod}");
        }

        $attributes = $this->readAttributeForMethod($classNamespaceWithMethod, $searchedAttributeNamespace);
        return !empty($attributes);
    }

    /**
     * Will attempt to read given attribute from classWithNamespace
     *
     * @param string $classNamespaceWithMethod
     * @param string $searchedAttributeNamespace
     * @return ReflectionAttribute[]
     * @throws ReflectionException
     * @throws Exception
     */
    private function readAttributeForMethod(string $classNamespaceWithMethod, string $searchedAttributeNamespace): array
    {
        if( !$this->isValidClassWithMethod($classNamespaceWithMethod) ){
            throw new Exception("Invalid classNamespaceWithMethod has been provided: {$classNamespaceWithMethod}");
        }

        $reflectionObject = new ReflectionMethod($classNamespaceWithMethod);
        $attributes       = $reflectionObject->getAttributes($searchedAttributeNamespace);

        return $attributes;
    }

    /**
     * Will check if the syntax is valid, should be:
     * classNamespace::methodName
     *
     * @param string $classNamespaceWithMethod
     * @return bool
     */
    private function isValidClassWithMethod(string $classNamespaceWithMethod): bool
    {
        if( !strstr($classNamespaceWithMethod, self::CLASS_AND_METHOD_SEPARATOR) ){
            $this->app->getLoggerService()->getLogger()->info("Incorrect syntax of classNamespaceWithMethod", [
                "classNamespaceWithMethod" => $classNamespaceWithMethod,
                "info"                     => "Missing characters: `" . self::CLASS_AND_METHOD_SEPARATOR . "`",
            ]);
            return false;
        }

        $classNamespaceWithMethodElements = explode(self::CLASS_AND_METHOD_SEPARATOR, $classNamespaceWithMethod);
        $countOfElements                  = count($classNamespaceWithMethodElements);
        if( $countOfElements != self::COUNT_OF_ELEMENTS_IN_CLASS_AND_METHOD ){
            $this->app->getLoggerService()->getLogger()->info("Incorrect syntax of classNamespaceWithMethod", [
                "classNamespaceWithMethod" => $classNamespaceWithMethod,
                "info"                     => "To many elements after exploding",
                "expected"                 => self::COUNT_OF_ELEMENTS_IN_CLASS_AND_METHOD,
                "got"                      => $countOfElements,
            ]);
            return false;
        }

        $methodName = $classNamespaceWithMethodElements[1];
        if( !preg_match("#" . self::METHOD_REGEX . "#", $methodName) ){
            $this->app->getLoggerService()->getLogger()->info("Incorrect method name was provided", [
                "classNamespaceWithMethod" => $classNamespaceWithMethod,
                "validationRegex"          => self::METHOD_REGEX,
                "methodName"               => $methodName,
            ]);
            return false;
        }

        return true;
    }


}