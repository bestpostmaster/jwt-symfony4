<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @groups("user:read", "file:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @groups("user:read", "file:read")
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     * @groups("user:read", "file:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $country;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $zipCode;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $preferredLanguage;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $typeOfAccount;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatarPicture;

    /**
     * @var string The hashed password
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getPreferredLanguage(): string
    {
        return $this->preferredLanguage;
    }

    /**
     * @param string $preferredLanguage
     */
    public function setPreferredLanguage(string $preferredLanguage): void
    {
        $this->preferredLanguage = $preferredLanguage;
    }

    /**
     * @return string
     */
    public function getTypeOfAccount(): string
    {
        return $this->typeOfAccount;
    }

    /**
     * @param string $typeOfAccount
     */
    public function setTypeOfAccount(string $typeOfAccount): void
    {
        $this->typeOfAccount = $typeOfAccount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAvatarPicture(): string
    {
        return $this->avatarPicture;
    }

    /**
     * @param string $avatarPicture
     */
    public function setAvatarPicture(string $avatarPicture): void
    {
        $this->avatarPicture = $avatarPicture;
    }

    /**
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string $dateOfBirth
     */
    public function setDateOfBirth(string $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return string
     */
    public function getIsBanned()
    {
        return $this->isBanned;
    }

    /**
     * @param string $isBanned
     */
    public function setIsBanned($isBanned): void
    {
        $this->isBanned = $isBanned;
    }

    /**
     * @var string The hashed password
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBanned = false;

    /**
     * @ORM\OneToMany(targetEntity=HostedFile::class, mappedBy="user")
     */
    private $files;

    /**
     * @ORM\Column(type="integer", length=60, nullable=true)
     * @groups("user:read", "file:read")
     */
    private int $totalSpaceUsedMo = 0;

    /**
     * @ORM\Column(type="integer", length=60, nullable=true)
     * @groups("user:read", "file:read")
     */
    private $authorizedSizeMo = 100;

    /**
     * @ORM\Column(type="datetime", length=60, nullable=true)
     * @groups("user:read", "file:read")
     */
    private \DateTimeInterface $registrationDate;

    /**
     * @return \DateTimeInterface
     */
    public function getRegistrationDate(): \DateTimeInterface
    {
        return $this->registrationDate;
    }

    /**
     * @param \DateTimeInterface $registrationDate
     */
    public function setRegistrationDate(\DateTimeInterface $registrationDate): void
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getLastConnexionDate(): \DateTimeInterface
    {
        return $this->lastConnexionDate;
    }

    /**
     * @param \DateTimeInterface $lastConnexionDate
     */
    public function setLastConnexionDate(\DateTimeInterface $lastConnexionDate): void
    {
        $this->lastConnexionDate = $lastConnexionDate;
    }

    /**
     * @return string
     */
    public function getSecretTokenForValidation(): string
    {
        return $this->secretTokenForValidation;
    }

    /**
     * @param string $secretTokenForValidation
     */
    public function setSecretTokenForValidation(string $secretTokenForValidation): void
    {
        $this->secretTokenForValidation = $secretTokenForValidation;
    }

    private \DateTimeInterface $lastConnexionDate;

    private string $secretTokenForValidation;

    /**
     * @return int
     */
    public function getTotalSpaceUsedMo(): int
    {
        return $this->totalSpaceUsedMo;
    }

    /**
     * @param int $totalSpaceUsedMo
     */
    public function setTotalSpaceUsedMo(int $totalSpaceUsedMo): void
    {
        $this->totalSpaceUsedMo = $totalSpaceUsedMo;
    }

    public function getAuthorizedSizeMo(): ?int
    {
        return $this->authorizedSizeMo;
    }

    public function setAuthorizedSizeMo(int $authorizedSizeMo): self
    {
        $this->authorizedSizeMo = $authorizedSizeMo;

        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
