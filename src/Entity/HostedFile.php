<?php

namespace App\Entity;

use App\Repository\HostedFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=HostedFileRepository::class)
 */
class HostedFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @groups("file:read")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups("file:read")
     */
    private string $realDir;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups("file:read")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups("file:read")
     */
    private string $clientName;

    /**
     * @ORM\Column(type="datetime")
     * @groups("file:read")
     */
    private \DateTimeInterface $uploadDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="files")
     * @groups("file:read")
     */
    private UserInterface $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups("file:read")
     */
    private string $virtualDirectory;

    /**
     * Size in MB
     * @ORM\Column(type="float")
     * @groups("file:read")
     */
    private $size;

    /**
     * @ORM\Column(type="boolean")
     * @groups("file:read")
     */
    private $scaned;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @groups("file:read")
     */
    private $description;

    /**
     * @ORM\Column(type="bigint")
     * @groups("file:read")
     */
    private $downloadCounter;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @groups("file:read")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @groups("file:read")
     */
    private $uploadLocalisation;

    /**
     * @ORM\Column(type="boolean")
     * @groups("file:read")
     */
    private $copyrightIssue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @groups("file:read")
     */
    private $conversionsAvailable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealDir(): ?string
    {
        return $this->realDir;
    }

    public function setRealDir(string $realDir): self
    {
        $this->realDir = $realDir;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->uploadDate;
    }

    public function setUploadDate(\DateTimeInterface $uploadDate): self
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVirtualDirectory(): ?string
    {
        return $this->virtualDirectory;
    }

    public function setVirtualDirectory(string $virtualDirectory): self
    {
        $this->virtualDirectory = $virtualDirectory;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getScaned(): ?bool
    {
        return $this->scaned;
    }

    public function setScaned(bool $scaned): self
    {
        $this->scaned = $scaned;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDownloadCounter(): ?string
    {
        return $this->downloadCounter;
    }

    public function setDownloadCounter(string $downloadCounter): self
    {
        $this->downloadCounter = $downloadCounter;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUploadLocalisation(): ?string
    {
        return $this->uploadLocalisation;
    }

    public function setUploadLocalisation(?string $uploadLocalisation): self
    {
        $this->uploadLocalisation = $uploadLocalisation;

        return $this;
    }

    public function getCopyrightIssue(): ?bool
    {
        return $this->copyrightIssue;
    }

    public function setCopyrightIssue(bool $copyrightIssue): self
    {
        $this->copyrightIssue = $copyrightIssue;

        return $this;
    }

    public function getConversionsAvailable(): ?string
    {
        return $this->conversionsAvailable;
    }

    public function setConversionsAvailable(?string $conversionsAvailable): self
    {
        $this->conversionsAvailable = $conversionsAvailable;

        return $this;
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
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * @param string $clientName
     */
    public function setClientName(string $clientName): void
    {
        $this->clientName = $clientName;
    }
}
