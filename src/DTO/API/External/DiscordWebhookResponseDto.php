<?php

namespace App\DTO\API\External;

use App\DTO\AbstractDTO;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DiscordResponseDto
 * @package App\DTO\API\External
 */
class DiscordWebhookResponseDto extends AbstractDTO
{
    const FIELD_NAME_MESSAGE = "message";
    const FIELD_NAME_CODE    = "code";

    const DEFAULT_CODE    = Response::HTTP_BAD_REQUEST;
    const DEFAULT_MESSAGE = "Bad request";



    /**
     * @var string $message
     */
    private string $message;

    /**
     * @var int $code
     */
    private int $code;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * Will build the dto from json
     *
     * @param string $json
     * @return DiscordWebhookResponseDto
     */
    public static function fromJson(string $json): DiscordWebhookResponseDto
    {
        $dataArray = json_decode($json, true);

        $message = self::checkAndGetKey($dataArray, self::FIELD_NAME_MESSAGE, self::DEFAULT_MESSAGE);
        $code    = self::checkAndGetKey($dataArray, self::FIELD_NAME_CODE, self:: DEFAULT_CODE);

        $dto = new DiscordWebhookResponseDto();
        $dto->setMessage($message);
        $dto->setCode($code);

        return $dto;
    }

    /**
     * Will build dto OK response
     *
     * @return DiscordWebhookResponseDto
     */
    public static function buildOkResponse(): DiscordWebhookResponseDto
    {
        $dto = new DiscordWebhookResponseDto();
        $dto->setCode(Response::HTTP_OK);
        return $dto;
    }

    /**
     * Will build the bad request response
     *
     * @return DiscordWebhookResponseDto
     */
    public static function buildBadRequestResponse(): DiscordWebhookResponseDto
    {
        $dto = new DiscordWebhookResponseDto();
        $dto->setMessage(self::DEFAULT_MESSAGE);
        $dto->setCode(self::DEFAULT_CODE);

        return $dto;
    }

}