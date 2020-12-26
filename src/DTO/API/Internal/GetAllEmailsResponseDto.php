<?php


namespace App\DTO\API\Internal;


use App\DTO\API\BaseApiResponseDto;

class GetAllEmailsResponseDto extends BaseApiResponseDto
{

    const KEY_EMAILS_JSONS = "emailsJsons";

    private array $emailsJsons = [];

    /**
     * @return array
     */
    public function getEmailsJsons(): array
    {
        return $this->emailsJsons;
    }

    /**
     * @param array $emailsJsons
     */
    public function setEmailsJsons(array $emailsJsons): void
    {
        $this->emailsJsons = $emailsJsons;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                         = parent::toArray();
        $array[self::KEY_EMAILS_JSONS] = $this->getEmailsJsons();

        return $array;
    }
}