<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenantId = null;

    #[ORM\ManyToMany(targetEntity: Device::class, mappedBy: 'acces')]
    private Collection $devices;

    #[ORM\ManyToMany(targetEntity: Team::class, mappedBy: 'members')]
    private Collection $teams;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $workfield = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registerdate = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $friends = null;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function setRoles($roles): static
    {
        $this->roles[] = $roles;

        return $this;
    }

    public function setAllRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTenantId(): ?int
    {
        if ($this->tenantId !== null) {
            return $this->tenantId->getId();
        }
        return null;
    }


    public function setTenantId(?Tenant $tenantId): static
    {

        $this->tenantId = $tenantId;

        return $this;
    }

    /**
     * @return Collection<int, int>
     */
    public function getDevices(): Collection
    {
        return $this->devices->map(function ($device) {
            return $device->getName();
        });
    }

    public function addDevice(Device $device): static
    {
        if (!$this->devices->contains($device)) {
            $this->devices->add($device);
            $device->addAcce($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): static
    {
        if ($this->devices->removeElement($device)) {
            $device->removeAcce($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, int>
     */
    public function getTeams(): Collection
    {
        return $this->teams->map(function ($team) {
            return $team->getName();
        });
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addMember($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            $team->removeMember($this);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthdate()
    {
        if ($this->birthdate !== null) {
            return date('Y-m-d', $this->birthdate->getTimestamp());
        }
        return null;
    }

    public function setBirthdate(?string $birthdate): static
    {
        $birthdate = \DateTimeImmutable::createFromFormat('Y-m-d', $birthdate);
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getRegisterdate()
    {
        if ($this->registerdate !== null) {
            return date('Y-m-d', $this->registerdate->getTimestamp());
        }
        return null;
    }

    public function setRegisterdate(?string $registerdate): static
    {
        $registerdate = \DateTimeImmutable::createFromFormat('Y-m-d', $registerdate);
        $this->registerdate = $registerdate;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getWorkfield(): ?string
    {
        return $this->workfield;
    }

    public function setWorkfield(?string $workfield): static
    {
        $this->workfield = $workfield;

        return $this;
    }

    public function getFriends(): ?array
    {
        return $this->friends;
    }

    public function setOneFriend($friend): static
    {
        $this->friends[] = $friend;

        return $this;
    }

    public function setFriends(?array $friends): static
    {
        $this->friends = $friends;

        return $this;
    }
}
