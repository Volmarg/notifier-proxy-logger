<?php

namespace App\DTO\Modules\Mailing;

use App\DTO\AbstractDTO;

/**
 *
 * Class MailAccountDTO
 * @package App\DTO\Modules\Mailing
 */
 class MailAccountDTO extends AbstractDTO
{

    const KEY_ID       = "id";
    const KEY_CLIENT   = 'client';
    const KEY_NAME     = 'name';
    const KEY_LOGIN    = 'login';
    const KEY_PASSWORD = 'password';

     /**
      * @var int $id
      */
    private int $id;

    /**
     * @var string $client
     */
    private string $client = "";

    /**
     * @var string $name
     */
    private string $name = "";

    /**
     * @var string $login
     */
    private string $login = "";

    /**
     * @var string|null $password
     */
    private ?string $password = "";

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
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient(string $client): void
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
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
            self::KEY_ID       => $this->getId(),
            self::KEY_CLIENT   => $this->getClient(),
            self::KEY_NAME     => $this->getName(),
            self::KEY_LOGIN    => $this->getLogin(),
            self::KEY_PASSWORD => $this->getPassword(),
        ];
    }

     /**
      * Will create dto from json
      *
      * @param string $json
      * @return MailAccountDTO
      */
    public static function fromJson(string $json): MailAccountDTO
    {
        $dataArray = json_decode($json, true);

        $id       = self::checkAndGetKey($dataArray, self::KEY_ID, null);
        $client   = self::checkAndGetKey($dataArray, self::KEY_CLIENT, null);
        $name     = self::checkAndGetKey($dataArray, self::KEY_NAME, null);
        $login    = self::checkAndGetKey($dataArray, self::KEY_LOGIN, null);
        $password = self::checkAndGetKey($dataArray, self::KEY_PASSWORD, null);

        $dto = new MailAccountDTO();
        $dto->setId($id);
        $dto->setClient($client);
        $dto->setName($name);
        $dto->setLogin($login);
        $dto->setPassword($password);

        return $dto;
    }
}