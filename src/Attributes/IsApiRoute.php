<?php


namespace App\Attributes;

/**
 * This attribute means that given route is an API route,
 * no properties are present on purpose
 *
 * Class IsApiRoute
 * @package App\Attributes
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class IsApiRoute
{

}