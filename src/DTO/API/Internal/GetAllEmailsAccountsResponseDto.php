<?php


namespace App\DTO\API\Internal;


use App\DTO\API\BaseApiResponseDto;

/**
 * Class GetAllEmailsAccountsResponseDto
 * @package App\DTO\API\Internal
 */
class GetAllEmailsAccountsResponseDto extends BaseApiResponseDto
{
    const KEY_EMAILS_ACCOUNTS_JSONS = "emailsAccountsJsons";

    private array $emailsAccountsJsons = [];

    /**
     * @return array
     */
    public function getEmailsAccountsJsons(): array
    {
        return $this->emailsAccountsJsons;
    }

    /**
     * @param array $emailsAccountsJsons
     */
    public function setEmailsAccountsJsons(array $emailsAccountsJsons): void
    {
        $this->emailsAccountsJsons = $emailsAccountsJsons;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                                  = parent::toArray();
        $array[self::KEY_EMAILS_ACCOUNTS_JSONS] = $this->getEmailsAccountsJsons();

        return $array;
    }
}