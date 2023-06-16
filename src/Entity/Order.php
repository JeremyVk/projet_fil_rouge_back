<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\OrderItem;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Invoice\InvoiceController;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\OrderItem\BaseOrderItemInterface;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:order']],
    denormalizationContext: ['groups' => ['write:order']],
)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    uriTemplate: '/users/{id}/orders',
    operations: [ new GetCollection()],
    uriVariables: [
     'id' => new Link(
         fromProperty: 'orders',
         fromClass: User::class
     )
     ]
 )]
#[Get(
    uriTemplate: '/orders/{id}/invoice',
    controller: InvoiceController::class,
    read: false,
    name: 'invoice'
)]
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

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:order', 'write:order'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['read:order', 'write:order'])]
    private ?float $amount = null;

    #[ORM\OneToMany(mappedBy: 'ordered', targetEntity: OrderItem::class, cascade: ['persist'])]
    #[Groups(['read:order'])]
    private Collection $orderItems;

    #[ORM\Column(type: 'integer', name: 'shipping_amount')]
    #[Groups(['read:order'])]
    private ?int $shippingAmount = null;

    #[ORM\ManyToOne(targetEntity:Address::class)]
    #[Groups(['read:order'])]
    private Address $shippingAddress;

    #[ORM\Column]
    #[Groups(['write:order'])]
    private string $invoice;

    public function __construct()
    {
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
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(BaseOrderItemInterface $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrdered($this);
        }

        return $this;
    }

    public function removeOrderItem(BaseOrderItemInterface $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrdered() === $this) {
                $orderItem->setOrdered(null);
            }
        }

        return $this;
    }

    public function getShippingAmount(): ?int
    {
        return $this->shippingAmount;
    }

    public function setShippingAmount(?int $shippingAmount): void
    {
        $this->shippingAmount = $shippingAmount;
    }

    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(Address $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getInvoice(): string
    {
        return $this->invoice;
    }

    public function setInvoice(string $invoice)
    {
        $this->invoice = $invoice;
    }
}
