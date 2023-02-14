<?php

declare(strict_types=1);

namespace App\Entity\Abstract\BaseVariant;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Articles\Book\Book;
use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Variants\BookVariant;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use App\Filters\RequestBooksParameterFilter;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use App\Entity\Abstract\BaseArticle\BaseArticle;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseArticle\BaseArticleInterface;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;

#[ORM\Entity()]
#[ORM\Table('shop_product_variant')]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap(['BookVariant' => BookVariant::class])]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookVariant', 'read:baseVariant', 'read:article']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:baseVariant']],
    order: ['unitPrice' => 'ASC'],
)]
#[ApiFilter(RequestBooksParameterFilter::class, properties: ["id"])]
abstract class BaseVariant implements BaseVariantInterface
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

    public function getId(): int
    {
        return $this->id;
    }

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

    abstract function getParent(): BaseArticleInterface;


    abstract function setParent(BaseArticleInterface $parent): void;

}