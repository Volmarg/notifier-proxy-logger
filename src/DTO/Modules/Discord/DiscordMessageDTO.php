<?php

namespace App\DTO\Modules\Discord;

use App\DTO\AbstractDTO;
use App\Entity\Modules\Discord\DiscordMessage;
use DateTime;

/**
 * Class DiscordMessageDTO
 * @package App\DTO\Modules\Discord
 */
class DiscordMessageDTO extends AbstractDTO
{

    const KEY_WEBHOOK_NAME    = 'webhookName';
    const KEY_MESSAGE_CONTENT = 'messageContent';
    const KEY_MESSAGE_TITLE   = 'messageTitle';
    const KEY_SOURCE          = 'source';
    const KEY_STATUS          = 'status';
    const KEY_CREATED         = 'created';

    /**
     * @var string $webhookName
     */
    private string $webhookName = "";

    /**
     * @var string $messageContent
     */
    private string $messageContent = "";

    /**
     * @var string $messageTitle
     */
    private string $messageTitle = "";

    /**
     * @var string $source
     */
    private string $source = "";

    /**
     * @var string $status
     */
    private string $status = "";

    /**
     * @var string $created
     */
    private string $created = "";

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
    public function getMessageTitle(): string
    {
        return $this->messageTitle;
    }

    /**
     * @param string $messageTitle
     */
    public function setMessageTitle(string $messageTitle): void
    {
        $this->messageTitle = $messageTitle;
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(string $created): void
    {
        $this->created = $created;
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
            self::KEY_MESSAGE_TITLE   => $this->getMessageContent(),
            self::KEY_SOURCE          => $this->getSource(),
            self::KEY_STATUS          => $this->getStatus(),
            self::KEY_CREATED         => $this->getCreated(),
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

        $defaultStatus  = DiscordMessage::STATUS_PENDING;
        $defaultCreated = (new DateTime())->format("Y-m-d H:i:s");

        $webhookName    = self::checkAndGetKey($dataArray, self::KEY_WEBHOOK_NAME, null);
        $messageContent = self::checkAndGetKey($dataArray, self::KEY_MESSAGE_CONTENT, null);
        $messageTitle   = self::checkAndGetKey($dataArray, self::KEY_MESSAGE_TITLE, null);
        $source         = self::checkAndGetKey($dataArray, self::KEY_SOURCE, null);
        $status         = self::checkAndGetKey($dataArray, self::KEY_STATUS, $defaultStatus);
        $created        = self::checkAndGetKey($dataArray, self::KEY_CREATED, $defaultCreated);

        $dto = new DiscordMessageDTO();
        $dto->setWebhookName($webhookName);
        $dto->setMessageContent($messageContent);
        $dto->setMessageTitle($messageTitle);
        $dto->setMessageContent($source);
        $dto->setStatus($status);
        $dto->setCreated($created);

        return $dto;
    }
}