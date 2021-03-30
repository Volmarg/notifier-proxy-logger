<?php


namespace App\DTO\Modules\Mailing;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @see SendTestMailForm
 *
 * Class SendTestMailDTO
 * @package App\DTO\Modules\Mailing
 */
class SendTestMailDTO
{
    /**
     * @var string $receiver
     */
    private string $receiver = "";
    /**
     * @var string $messageBody
     */
    private string $messageBody = "";
    /**
     * @var string $messageTitle
     */
    private string $messageTitle = "";
    /**
     * @var string $submit
     */
    private string $submit = "";

    /**
     * @var ?string $account
     */
    private ?string $account = null;

    /**
     * @return string
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     */
    public function setReceiver(string $receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * @return string
     */
    public function getMessageBody(): string
    {
        return $this->messageBody;
    }

    /**
     * @param string $messageBody
     */
    public function setMessageBody(string $messageBody): void
    {
        $this->messageBody = $messageBody;
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
    public function getSubmit(): string
    {
        return $this->submit;
    }

    /**
     * @param string $submit
     */
    public function setSubmit(string $submit): void
    {
        $this->submit = $submit;
    }

    /**
     * @return string|null
     */
    public function getAccount(): ?string
    {
        return $this->account;
    }

    /**
     * @param string|null $account
     */
    public function setAccount(?string $account): void
    {
        $this->account = $account;
    }

    /**
     * Set of rules to validate the dto with the validator
     *
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // Receiver
        $metadata->addPropertyConstraint("receiver", new Email());

        // MessageBody
        $metadata->addPropertyConstraint('messageBody', new NotBlank());

        // MessageTitle
        $metadata->addPropertyConstraint('messageTitle', new NotBlank());
    }

}