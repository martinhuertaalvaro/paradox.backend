<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'devices')]
    private Collection $acces;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenantId = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    public function __construct()
    {
        $this->acces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAcces(): Collection
    {
        return $this->acces;
    }

    public function addAcce(User $acce): static
    {
        if (!$this->acces->contains($acce)) {
            $this->acces->add($acce);
        }

        return $this;
    }

    public function removeAcce(User $acce): static
    {
        $this->acces->removeElement($acce);

        return $this;
    }

    public function getTenantId(): ?Tenant
    {
        return $this->tenantId;
    }

    public function setTenantId(?Tenant $tenantId): static
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }
}
