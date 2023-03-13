<?php

namespace App\Entity;

use App\Entity\Order;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\OrderItemRepository;
use App\Entity\Abstract\BaseVariant\BaseVariant;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Entity\Abstract\OrderItem\BaseOrderItemInterface;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ApiResource]
class OrderItem implements BaseOrderItemInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $ordered = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:order'])]
    private ?BaseVariant $variant = null;

    #[ORM\Column]
    #[Groups(['read:order'])]
    private ?int $quantity = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['read:order'])]
    private ?int $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdered(): ?Order
    {
        return $this->ordered;
    }

    public function setOrdered(?Order $ordered): void
    {
        $this->ordered = $ordered;
    }

    public function getVariant(): ?BaseVariantInterface
    {
        return $this->variant;
    }

    public function setVariant(BaseVariantInterface $variant): void
    {
        $this->variant = $variant;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setprice(?int $price): void
    {
        $this->price = $price;
    }
}
