<?php

namespace App\DTO\API\Internal\Email;

use App\Action\API\External\MailingExternalApiAction;
use App\DTO\API\BaseApiResponseDto;

/**
 * Handles the {@see MailingExternalApiAction::insertMail()}
 */
class InsertEmailResponseDto extends BaseApiResponseDto
{
    private const KEY_ID = "id";

    /**
     * @var int $id
     */
    private int $id;

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
     * @return array
     */
    public function toArray(): array
    {
        $array               = parent::toArray();
        $array[self::KEY_ID] = $this->getId();

        return $array;
    }
}