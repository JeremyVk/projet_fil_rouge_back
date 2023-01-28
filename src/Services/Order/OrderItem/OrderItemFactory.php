<?php

namespace App\Services\Order\OrderItem;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Entity\Abstract\OrderItem\BaseOrderItemInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class OrderItemFactory
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {  
    }

    public function buildOrderdItem(BaseVariantInterface $baseVariant, int $quantity): BaseOrderItemInterface
    {
        $orderItem = new OrderItem();
        $orderItem->setVariant($baseVariant);
        $stockDecremented = $baseVariant->getStock() - $quantity;

        if($stockDecremented < 0) {
            throw new Exception("A Product is not in stock");
        }

        $baseVariant->setStock($stockDecremented);
        $this->entityManager->persist($baseVariant);

        $orderItem->setPrice($baseVariant->getUnitPrice());
        $orderItem->setQuantity($quantity);
        return $orderItem;
    }
}