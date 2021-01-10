<?php

namespace App\DTO\Modules\Discord;

use App\DTO\AbstractDTO;

/**
 * Class DiscordMessageDTO
 * @package App\DTO\Modules\Discord
 */
class DiscordMessageDTO extends AbstractDTO
{

    const KEY_WEBHOOK_NAME    = 'webhookName';
    const KEY_MESSAGE_CONTENT = 'messageContent';
    const KEY_SOURCE          = 'source';

    /**
     * @var string $webhookName
     */
    private string $webhookName = "";

    /**
     * @var string $messageContent
     */
    private string $messageContent = "";

    /**
     * @var string $source
     */
    private string $source = "";

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
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
     * @return string
     */
    public function getMessageContent(): string
    {
        return $this->messageContent;
    }

    /**
     * @param string $messageContent
     */
    public function setMessageContent(string $messageContent): void
    {
        $this->messageContent = $messageContent;
    }

    /**
     * Returns dto data in form of string
     *
     * @return string
     */
    public function toJson(): string
    {
        $dataArray = $this->toArray();
        return json_encode($dataArray);
    }

    /**
     * Returns dto data in form of array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_WEBHOOK_NAME    => $this->getWebhookName(),
            self::KEY_MESSAGE_CONTENT => $this->getMessageContent(),
        ];
    }

    /**
     * Will create dto from json
     *
     * @param string $json
     * @return DiscordMessageDTO
     */
    public static function fromJson(string $json): DiscordMessageDTO
    {
        $dataArray = json_decode($json, true);

        $webhookName    = self::checkAndGetKey($dataArray, self::KEY_WEBHOOK_NAME, null);
        $messageContent = self::checkAndGetKey($dataArray, self::KEY_MESSAGE_CONTENT, null);
        $source         = self::checkAndGetKey($dataArray, self::KEY_SOURCE, null);

        $dto = new DiscordMessageDTO();
        $dto->setWebhookName($webhookName);
        $dto->setMessageContent($messageContent);
        $dto->setMessageContent($source);

        return $dto;
    }
}