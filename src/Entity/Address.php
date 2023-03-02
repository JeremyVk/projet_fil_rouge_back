<?php

namespace App\Entity;

use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\AddressRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    normalizationContext: ['read:address', 'read:user'],
    denormalizationContext: ['write:address', 'write:user'])]
#[ApiResource(
   uriTemplate: '/users/{id}/addresses',
   uriVariables: [
    'id' => new Link(
        fromClass: User::class,
        fromProperty: 'addresses'
    )
    ],
   operations: [ new GetCollection(), new Put()]
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:order'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:order'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:order'])]
    private ?string $street = null;

    #[ORM\Column(length: 8)]
    #[Groups(['read:order'])]
    private ?string $postalCode = null;

    #[ORM\ManyToOne(inversedBy: 'addresses', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['write:users'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function __toString(): string
    {
        return $this->firstname . ' ' . $this->lastname . ' ' . $this->street . ' ' . $this->postalCode . '';
    }
}
