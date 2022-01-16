<?php

namespace App\Entity\Modules\Mailing;

use App\Entity\EntityInterface;
use App\Repository\Modules\Mailing\MailRepository;
use App\Validation\Constraint\ArrayOfEmailsConstraint;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class Mail implements EntityInterface
{
    private const TYPE_NOTIFICATION = "NOTIFICATION";
    private const TYPE_PLAIN        = "PLAIN";

    const FIELD_NAME_STATUS = "status";

    const STATUS_SENT    = "SENT";
    const STATUS_PENDING = "PENDING";
    const STATUS_ERROR   = "ERROR";

    const PROCESSABLE_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ERROR,
    ];

    const ALL_STATUSES = [
        self::STATUS_SENT,
        self::STATUS_PENDING,
        self::STATUS_ERROR,
    ];

    const SOURCE_PMS = "PMS";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fromEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $type;

    /**
     * @ORM\Column(type="json")
     */
    private $toEmails = [];

    public function __construct()
    {
        $this->created = new DateTime();
        $this->status  = self::STATUS_PENDING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromEmail(): ?string
    {
        return $this->fromEmail;
    }

    public function setFromEmail(string $fromEmail): self
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getToEmails(): ?array
    {
        return $this->toEmails;
    }

    public function setToEmails(array $toEmails): self
    {
        $this->toEmails = $toEmails;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Will check if mail is of type notification,
     * E-Mails of this type are utilizing the: {@see Notification}
     *
     * @return bool
     */
    public function isNotificationEmail(): bool
    {
        return ($this->getType() === self::TYPE_NOTIFICATION);
    }

    /**
     * Return information if E-mail is plain type, which means it just sends whatever there is to be sent,
     * no extra symfony based formatting
     *
     * @return bool
     */
    public function isPlainEmail(): bool
    {
       return ($this->getType() === self::TYPE_PLAIN);
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // FromEmail
        $metadata->addPropertyConstraint('fromEmail', new NotBlank());
        $metadata->addPropertyConstraint('fromEmail', new Email());

        // Subject
        $metadata->addPropertyConstraint("subject", new NotBlank());

        // Body
        $metadata->addPropertyConstraint("body", new NotBlank());

        // Status
        $metadata->addPropertyConstraint("status", new NotBlank());
        $metadata->addPropertyConstraint("status", new Choice([
            "choices" => self::ALL_STATUSES
        ]));

        // Created
        $metadata->addPropertyConstraint("created", new NotBlank());

        // Source
        $metadata->addPropertyConstraint("source", new NotBlank());

        // ToEmails
        $metadata->addPropertyConstraint('toEmails', new NotBlank());
        $metadata->addPropertyConstraint('toEmails', new ArrayOfEmailsConstraint());

    }
}
