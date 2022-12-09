<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookVariantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookVariantRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookVariant',]],
    denormalizationContext: ['groups' => ['write:bookVariant']]
)]
class BookVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:books', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookVariants')]
    #[ORM\JoinColumn(nullable: false, name: 'book_id')]
    #[Groups(['read:books', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private Book $book;

    #[ORM\Column(name: 'stock')]
    #[Groups(['read:books', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private int $stock;

    #[ORM\Column(name: 'unit_price')]
    #[Groups(['read:books', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private ?float $unitPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }
}
