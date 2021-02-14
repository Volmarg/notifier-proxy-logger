<?php

namespace App\Controller\Core;

use App\Controller\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Env extends AbstractController {

    /**
     * @return bool
     */
    public static function isDemo() {
        try {
            $is_demo = Utils::getBoolRepresentationOfBoolString($_ENV['APP_DEMO']);
        } catch (\Exception $e) {
            $is_demo = false;
        }
        return $is_demo;
    }

}
