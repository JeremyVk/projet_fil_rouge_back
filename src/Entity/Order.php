<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\BookVariant;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use App\Entity\Abstract\BaseArticle;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\CreateOrderController;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:order']],
    // denormalizationContext: ['groups' => ['write:order']],
    operations: [
        new Get(),
        new Post(
            name: 'publication', 
            uriTemplate: '/orders', 
            controller: CreateOrderController::class
        )
    ]
)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:order', 'write:order'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read:order', 'write:order'])]
    private ?int $number = null;

    #[ORM\Column]
    #[Groups(['read:order', 'write:order'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:order', 'write:order'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['read:order', 'write:order'])]
    private ?float $amount = null;

    #[ORM\ManyToMany(targetEntity: BookVariant::class, inversedBy: 'orders')]
    #[Groups(['read:order', 'write:order'])]
    private Collection $books;

    #[ORM\OneToMany(mappedBy: 'ordered', targetEntity: OrderItem::class)]
    private Collection $orderItems;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection<int, BaseArticle>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(BookVariant $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(BookVariant $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrdered($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrdered() === $this) {
                $orderItem->setOrdered(null);
            }
        }

        return $this;
    }
}
