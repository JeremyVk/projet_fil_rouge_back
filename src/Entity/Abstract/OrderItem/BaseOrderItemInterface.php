<?php

namespace App\Entity\Abstract\OrderItem;

use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Entity\Order;

interface BaseOrderItemInterface
{
    public function getId(): ?int;

    public function getOrdered(): ?Order;
    public function setOrdered(Order $order): void;

    public function getQuantity(): ?int;
    public function setQuantity(int $quantity): void;

    public function getPrice(): ?int;
    public function setPrice(int $price): void;

    public function getVariant(): ?BaseVariantInterface;
    public function setVariant(BaseVariantInterface $variant): void;
}