<?php

namespace App\Entity\Modules\Mailing;

use App\Repository\Modules\Mailing\MailAttachmentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=MailAttachmentRepository::class)
 * @ORM\Table(name="mail_attachment", uniqueConstraints={
 *  @UniqueConstraint(name="unique_file_name", columns={"file_name", "email_id"})
 * })
 */
class MailAttachment
{
    private const FILE_TYPE_FILE  = "FILE";
    private const FILE_TYPE_IMAGE = "IMAGE";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $created;

    /**
     * @ORM\ManyToOne(targetEntity=Mail::class, inversedBy="Modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private Mail $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $fileName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $fileType;

    /**
     * @param string $path
     * @param string $fileName
     * @param string $fileType
     */
    public function __construct(string $path, string $fileName, string $fileType)
    {
        $this->created  = new DateTime();
        $this->path     = $path;
        $this->fileName = $fileName;
        $this->fileType = $fileType;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?Mail
    {
        return $this->email;
    }

    public function setEmail(?Mail $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Will check if attachment is an image
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->getFileType() === self::FILE_TYPE_IMAGE;
    }
}
