<?php

namespace App\DTO\Modules\Mailing;

use App\DTO\AbstractDTO;

/**
 * Class MailDTO
 * @package App\DTO\Modules\Mailing
 */
class MailDTO extends AbstractDTO
{

    const KEY_FROM_EMAIL  = 'fromEmail';
    const KEY_SUBJECT     = 'subject';
    const KEY_BODY        = 'body';
    const KEY_STATUS      = 'status';
    const KEY_CREATED     = 'created';
    const KEY_SOURCE      = 'source';
    const KEY_TO_EMAILS   = 'toEmails';
    const KEY_ATTACHMENTS = 'attachments';
    const KEY_EMAIL_TYPE  = "emailType";

    /**
     * @var string $fromEmail
     */
    private string $fromEmail = "";

    /**
     * @var string $emailType
     */
    private string $emailType = "";

    /**
     * @var string $subject
     */
    private string $subject = "";

    /**
     * @var string $body
     */
    private string $body = "";

    /**
     * @var string|null $status
     */
    private ?string $status = "";

    /**
     * @var string|null $created
     */
    private ?string $created = "";

    /**
     * @var string $source
     */
    private string $source = "";

    /**
     * @var array $toEmails
     */
    private array $toEmails = [];

    /**
     * Key is a file name, value is file_content {@see file_get_contents()}
     *
     * @var Array<string> $attachments
     */
    private array $attachments = [];

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     */
    public function setFromEmail(string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getCreated(): ?string
    {
        return $this->created;
    }

    /**
     * @param string|null $created
     */
    public function setCreated(?string $created): void
    {
        $this->created = $created;
    }

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
     * @return array
     */
    public function getToEmails(): array
    {
        return $this->toEmails;
    }

    /**
     * @param array $toEmails
     */
    public function setToEmails(array $toEmails): void
    {
        $this->toEmails = $toEmails;
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
     * @return string[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param string[] $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * @return string
     */
    public function getEmailType(): string
    {
        return $this->emailType;
    }

    /**
     * @param string $emailType
     */
    public function setEmailType(string $emailType): void
    {
        $this->emailType = $emailType;
    }

    /**
     * Returns dto data in form of array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_FROM_EMAIL  => $this->getFromEmail(),
            self::KEY_SUBJECT     => $this->getSubject(),
            self::KEY_BODY        => $this->getBody(),
            self::KEY_STATUS      => $this->getStatus(),
            self::KEY_CREATED     => $this->getCreated(),
            self::KEY_SOURCE      => $this->getSource(),
            self::KEY_TO_EMAILS   => $this->getToEmails(),
            self::KEY_ATTACHMENTS => $this->getAttachments(),
            self::KEY_EMAIL_TYPE  => $this->getEmailType(),
        ];
    }

    /**
     * Will create dto from json
     *
     * @param string $json
     * @return MailDTO
     */
    public static function fromJson(string $json): MailDTO
    {
        $dataArray = json_decode($json, true);

        $fromEmail   = self::checkAndGetKey($dataArray, self::KEY_FROM_EMAIL, null);
        $subject     = self::checkAndGetKey($dataArray, self::KEY_SUBJECT, null);
        $body        = self::checkAndGetKey($dataArray, self::KEY_BODY, null);
        $status      = self::checkAndGetKey($dataArray, self::KEY_STATUS, null);
        $created     = self::checkAndGetKey($dataArray, self::KEY_CREATED, null);
        $source      = self::checkAndGetKey($dataArray, self::KEY_SOURCE, null);
        $toEmails    = self::checkAndGetKey($dataArray, self::KEY_TO_EMAILS, null);
        $attachments = self::checkAndGetKey($dataArray, self::KEY_ATTACHMENTS, []);
        $emailType   = self::checkAndGetKey($dataArray, self::KEY_EMAIL_TYPE, "");

        $dto = new MailDTO();
        $dto->setFromEmail($fromEmail);
        $dto->setSubject($subject);
        $dto->setBody($body);
        $dto->setStatus($status);
        $dto->setCreated($created);
        $dto->setSource($source);
        $dto->setToEmails($toEmails);
        $dto->setAttachments($attachments);
        $dto->setEmailType($emailType);

        return $dto;
    }
}