<?php

namespace App\Controller\Core;

use App\Controller\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Env extends AbstractController {

    public static function getUploadDir() {
        return $_ENV['UPLOAD_DIR'];
    }

    public static function getImagesUploadDir() {
        return $_ENV['IMAGES_UPLOAD_DIR'];
    }

    public static function getVideoUploadDir() {
        return $_ENV['VIDEOS_UPLOAD_DIR'];
    }

    public static function getMiniaturesUploadDir() {
        return $_ENV['MINIATURES_UPLOAD_DIR'];
    }

    public static function getPublicRootDir(){
        return $_ENV['PUBLIC_ROOT_DIR'];
    }

    public static function getFilesUploadDir() {
        return $_ENV['FILES_UPLOAD_DIR'];
    }

    public static function getDatabaseUrl() {
        return $_ENV['DATABASE_URL'];
    }

    /**
     * Returns the information whether the guide mode is on/off
     * @return bool
     */
    public static function isGuide() {
        try {
            $is_guide = Utils::getBoolRepresentationOfBoolString($_ENV['APP_GUIDE']);
        } catch (\Exception $e) {
            $is_guide = false;
        }
        return $is_guide;
    }

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

    /**
     * @return bool
     */
    public static function isMaintenance(): bool {
        try {
            $is_maintenance = Utils::getBoolRepresentationOfBoolString($_ENV['APP_MAINTENANCE']);
        } catch (\Exception $e) {
            $is_maintenance = false;
        }
        return $is_maintenance;
    }


}
