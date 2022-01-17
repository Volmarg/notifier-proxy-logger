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
     * @var string $relativeMailAttachmentsFolder
     */
    private string $relativeMailAttachmentsFolder;

    /**
     * @var string $absoluteMailAttachmentsFolder
     */
    private string $absoluteMailAttachmentsFolder;

    /**
     * @var int $getAllAttachmentsMaxSizeMb
     */
    private int $getAllAttachmentsMaxSizeMb;

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

    /**
     * @return string
     */
    public function getRelativeMailAttachmentsFolder(): string
    {
        return $this->relativeMailAttachmentsFolder;
    }

    /**
     * @param string $relativeMailAttachmentsFolder
     */
    public function setRelativeMailAttachmentsFolder(string $relativeMailAttachmentsFolder): void
    {
        $this->relativeMailAttachmentsFolder = $relativeMailAttachmentsFolder;
    }

    /**
     * @return string
     */
    public function getAbsoluteMailAttachmentsFolder(): string
    {
        return $this->absoluteMailAttachmentsFolder;
    }

    /**
     * @param string $absoluteMailAttachmentsFolder
     */
    public function setAbsoluteMailAttachmentsFolder(string $absoluteMailAttachmentsFolder): void
    {
        $this->absoluteMailAttachmentsFolder = $absoluteMailAttachmentsFolder;
    }

    /**
     * @return int
     */
    public function getGetAllAttachmentsMaxSizeMb(): int
    {
        return $this->getAllAttachmentsMaxSizeMb;
    }

    /**
     * @param int $getAllAttachmentsMaxSizeMb
     */
    public function setGetAllAttachmentsMaxSizeMb(int $getAllAttachmentsMaxSizeMb): void
    {
        $this->getAllAttachmentsMaxSizeMb = $getAllAttachmentsMaxSizeMb;
    }

}