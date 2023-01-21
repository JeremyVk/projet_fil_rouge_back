<?php

declare(strict_types=1);

namespace App\Entity\Abstract;

use App\Entity\Abstract\BaseArticle;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\MappedSuperclass()]
abstract class BaseVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:article', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private ?int $id = null;

    #[ORM\Column(name: 'stock')]
    #[Groups(['read:bookVariant', 'write:baseVariant', "read:article"])]
    private int $stock;

    #[ORM\Column(name: 'unit_price')]
    #[Groups(['read:baseVariant', 'write:baseVariant', "read:article"])]
    private float $unitPrice;

    #[Groups(['read:baseVariant', 'write:baseVariant', "read:article"])]
    private BaseArticle $parent;

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

    public function getParent(): BaseArticle
    {
        return $this->parent;
    }

    public function setParent(BaseArticle $book): void
    {
        $this->parent = $book;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}