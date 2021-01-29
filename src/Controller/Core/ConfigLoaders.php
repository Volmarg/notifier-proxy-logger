<?php


namespace App\Controller\Core;


use App\Services\Internal\ConfigLoaders\SystemDataConfigLoader;

class ConfigLoaders
{

    /**
     * @var SystemDataConfigLoader $systemDataConfigLoader
     */
    private SystemDataConfigLoader $systemDataConfigLoader;

    /**
     * @return SystemDataConfigLoader
     */
    public function getSystemDataConfigLoader(): SystemDataConfigLoader
    {
        return $this->systemDataConfigLoader;
    }

    /**
     * @param SystemDataConfigLoader $systemDataConfigLoader
     */
    public function setSystemDataConfigLoader(SystemDataConfigLoader $systemDataConfigLoader): void
    {
        $this->systemDataConfigLoader = $systemDataConfigLoader;
    }

}