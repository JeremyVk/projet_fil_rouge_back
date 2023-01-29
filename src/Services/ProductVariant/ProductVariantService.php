<?php

namespace App\Services\ProductVariant;

use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }
}