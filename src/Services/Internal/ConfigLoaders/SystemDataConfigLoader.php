<?php


namespace App\Services\Internal\ConfigLoaders;

/**
 * Class SystemDataConfigLoader
 * @package App\Services\Internal\ConfigLoaders
 */
class SystemDataConfigLoader
{

    /**
     * @var string $fromMail
     */
    private string $fromMail;

    /**
     * @return string
     */
    public function getFromMail(): string
    {
        return $this->fromMail;
    }

    /**
     * @param string $fromMail
     */
    public function setFromMail(string $fromMail): void
    {
        $this->fromMail = $fromMail;
    }

}