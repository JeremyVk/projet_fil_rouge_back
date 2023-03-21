<?php

namespace App\Entity;

use Assert\NotBlank;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\State\UserPostProcessor;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\GetUserController;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\ResetPasswordController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:users']],
    denormalizationContext: ['groups' => ['write:users']],
)]
#[Post(processor: UserPostProcessor::class)]
#[Put(
    name: "reset_password",
    uriTemplate: '/users/reset_password',
    controller: ResetPasswordController::class,
    read: false,
    processor: UserPostProcessor::class
)]
#[Put()]
#[GetCollection(
    name: 'getMe',
    uriTemplate: '/getMe',
    controller: GetUserController::class,
    normalizationContext: ['groups' => 'read:users']
)]
#[GetCollection()]
#[Collection(
    operations: ['put', "get', 'delete'"]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:users', 'read:order'])]
    private int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['read:users', 'write:users'])]
    private string $email;

    #[ORM\Column]
    #[Groups(['read:users', 'write:users'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['write:users'])]
    private string $password;

    // #[Assert\NotBlank(groups: ['write:users'])]
    #[Groups(['write:users'])]
    private ?string $plainPassword = null;

    #[ORM\Column(name: 'firstname', length: 100)]
    #[Groups(['read:users', 'write:users'])]
    private string $firstname ;

    #[ORM\Column(name: 'lastname', length: 255)]
    #[Groups(['read:users', 'write:users'])]
    private string $lastname;

    #[ORM\OneToMany(mappedBy: 'user', cascade: ['persist', 'remove'], targetEntity: Address::class)]
    #[Groups(['read:users'])]
    private ?Collection $addresses = null;


    #[ORM\OneToMany(mappedBy: 'user', cascade: ['persist', 'remove'], targetEntity: Order::class)]
    #[Groups(['read:users', 'write:users'])]
    private ?Collection $orders = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getAddresses(): ?Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): void
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setUser($this);
        }
    }

    public function removeAddress(Address $address): void
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            $address->setUser(null);
        }
    }

    public function getOrders(): ?Collection
    {
        return $this->orders;
    }
}
