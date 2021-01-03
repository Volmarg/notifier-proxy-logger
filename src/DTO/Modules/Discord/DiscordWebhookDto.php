<?php

namespace App\DTO\Modules\Discord;

/**
 * Class DiscordWebhookDto
 */
class DiscordWebhookDto
{

    const KEY_ID           = "id";
    const KEY_USERNAME     = "username";
    const KEY_WEBHOOK_URL  = "webhookUrl";
    const KEY_DESCRIPTION  = "description";
    const KEY_WEBHOOK_NAME = "webhookName";

    /**
     * @var int $id
     */
    private int $id;

    /**
     * @var string $username
     */
    private string $username;

    /**
     * @var string $webhookUrl
     */
    private string $webhookUrl;

    /**
     * @var string $description
     */
    private string $description;

    /**
     * @var string $webhookName
     */
    private string $webhookName;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }

    /**
     * @param string $webhookUrl
     */
    public function setWebhookUrl(string $webhookUrl): void
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getWebhookName(): string
    {
        return $this->webhookName;
    }

    /**
     * @param string $webhookName
     */
    public function setWebhookName(string $webhookName): void
    {
        $this->webhookName = $webhookName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Returns array representation of the dto
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_DESCRIPTION  => $this->getDescription(),
            self::KEY_USERNAME     => $this->getUsername(),
            self::KEY_WEBHOOK_NAME => $this->getWebhookName(),
            self::KEY_WEBHOOK_URL  => $this->getWebhookUrl(),
            self::KEY_ID           => $this->getId(),
        ];
    }

    /**
     * Returns the json representation of the dto
     *
     * @return string
     */
    public function toJson(): string
    {
        $dataArray = $this->toArray();
        $json      = json_encode($dataArray);

        return $json;
    }
}