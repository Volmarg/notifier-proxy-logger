<?php


namespace App\DTO\API\Internal\Email;

use App\Action\API\External\MailingExternalApiAction;
use App\DTO\API\BaseApiResponseDto;

/**
 * Handles the {@see MailingExternalApiAction::getMailStatus()}
 */
class GetEmailStatusResponseDto extends BaseApiResponseDto
{

    private const KEY_STATUS = "status";

    /**
     * @var string $status
     */
    private string $status;

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
     * @return array
     */
    public function toArray(): array
    {
        $array                   = parent::toArray();
        $array[self::KEY_STATUS] = $this->getStatus();

        return $array;
    }
}