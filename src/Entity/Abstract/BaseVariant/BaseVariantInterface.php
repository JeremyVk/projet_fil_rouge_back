<?php

declare(strict_types=1);

namespace App\Entity\Abstract\BaseVariant;

use App\Entity\Abstract\BaseArticle\BaseArticleInterface;
use App\Entity\Tax;

interface BaseVariantInterface
{
    public function getId(): int;

    public function getStock(): int;
    public function setStock(int $stock): void;

    public function getUnitPrice(): float;
    public function setUnitPrice(float $unitPrice): void;

    public function getParent(): BaseArticleInterface;
    public function setParent(BaseArticleInterface $parent): void;

    public function getTax(): Tax;
    public function setTax(Tax $tax): void;
}