<?php

namespace App\Entity\Modules\Discord;

use App\Repository\Modules\Discord\DiscordWebhookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Table(name="discord_webhook", uniqueConstraints={
 *  @UniqueConstraint(name="unique_name", columns={"webhook_name"})
 * })
 * @ORM\Entity(repositoryClass=DiscordWebhookRepository::class)
 */
class DiscordWebhook
{

    const FIELD_NAME_WEBHOOK_NAME = "webhookName";
    const FIELD_NAME_WEBHOOK_URL  = "webhookUrl";
    const FIELD_NAME_USERNAME     = "username";
    const FIELD_NAME_DESCRIPTION  = "description";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="text")
     */
    private $webhookUrl;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $webhookName;

    /**
     * @ORM\OneToMany(targetEntity=DiscordMessage::class, mappedBy="discordWebhook")
     */
    private $discordMessages;

    public function __construct()
    {
        $this->discordMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function setWebhookUrl(string $webhookUrl): self
    {
        $this->webhookUrl = $webhookUrl;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWebhookName(): ?string
    {
        return $this->webhookName;
    }

    public function setWebhookName(string $webhookName): self
    {
        $this->webhookName = $webhookName;

        return $this;
    }

    /**
     * @return Collection|DiscordMessage[]
     */
    public function getDiscordMessages(): Collection
    {
        return $this->discordMessages;
    }

    public function addDiscordMessage(DiscordMessage $discordMessage): self
    {
        if (!$this->discordMessages->contains($discordMessage)) {
            $this->discordMessages[] = $discordMessage;
            $discordMessage->setDiscordWebhook($this);
        }

        return $this;
    }

    public function removeDiscordMessage(DiscordMessage $discordMessage): self
    {
        if ($this->discordMessages->removeElement($discordMessage)) {
            // set the owning side to null (unless already changed)
            if ($discordMessage->getDiscordWebhook() === $this) {
                $discordMessage->setDiscordWebhook(null);
            }
        }

        return $this;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(self::FIELD_NAME_WEBHOOK_NAME, new NotBlank());
        $metadata->addPropertyConstraint(self::FIELD_NAME_WEBHOOK_URL, new NotBlank());
        $metadata->addPropertyConstraint(self::FIELD_NAME_USERNAME, new NotBlank());
        $metadata->addPropertyConstraint(self::FIELD_NAME_DESCRIPTION, new NotBlank());
    }
}
