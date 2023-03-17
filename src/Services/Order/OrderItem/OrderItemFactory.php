<?php

namespace App\Services\Order\OrderItem;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\EcommerceErrorException;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Entity\Abstract\OrderItem\BaseOrderItemInterface;

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
            throw new EcommerceErrorException(EcommerceErrorException::PRODUCT_STOCK_EMPTY);
        }

        $baseVariant->setStock($stockDecremented);
        $this->entityManager->persist($baseVariant);

        $orderItem->setPrice($baseVariant->getUnitPrice());
        $orderItem->setQuantity($quantity);
        $orderItem->setTax($baseVariant->getTax()->getAmount());

        return $orderItem;
    }
}