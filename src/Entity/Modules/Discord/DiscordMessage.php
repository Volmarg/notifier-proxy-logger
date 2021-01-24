<?php

namespace App\Entity\Modules\Discord;

use App\Entity\EntityInterface;
use App\Repository\Modules\Mailing\MailRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class DiscordMessage implements EntityInterface
{
    const FIELD_NAME_STATUS          = "status";
    const FIELD_NAME_MESSAGE_CONTENT = "messageContent";

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
    private ?int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $messageContent;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private string $messageTitle;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $created;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $source;

    /**
     * @ORM\ManyToOne(targetEntity=DiscordWebhook::class, inversedBy="discordMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $discordWebhook;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->status  = self::STATUS_PENDING;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
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
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // MessageContent
        $metadata->addPropertyConstraint('messageContent', new NotBlank());

    }

    public function getDiscordWebhook(): ?DiscordWebhook
    {
        return $this->discordWebhook;
    }

    public function setDiscordWebhook(?DiscordWebhook $discordWebhook): self
    {
        $this->discordWebhook = $discordWebhook;

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

}
