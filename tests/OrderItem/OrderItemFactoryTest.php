<?php

namespace App\Tests\OrderItem;

use App\Entity\OrderItem;
use App\Entity\Tax;
use App\Entity\Variants\BookVariant;
use App\Exceptions\EcommerceErrorException;
use App\Services\Order\OrderItem\OrderItemFactory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class OrderItemFactoryTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private OrderItemFactory $orderItemFactory;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->orderItemFactory = new OrderItemFactory($this->entityManager);
    }

    public function testBuildOrderItemWithGoodData()
    {
        $tax = new Tax();
        $tax->setAmount(0.005);

        $productVariant = new BookVariant();
        $productVariant->setStock(10);
        $productVariant->setUnitPrice(1500);
        $productVariant->setTax($tax);

        $expectedOrderItem = new OrderItem();
        $expectedOrderItem->setQuantity(5);
        $expectedOrderItem->setPrice(1500);
        $expectedOrderItem->setTax($tax->getAmount());
        $expectedOrderItem->setVariant($productVariant);

        $orderItem = $this->orderItemFactory->buildOrderdItem($productVariant, 5);

        $this->assertEquals($expectedOrderItem, $orderItem);
        $this->assertEquals(5, $productVariant->getStock());
    }

    public function testBuildOrderItemWithNoStock()
    {
        $tax = new Tax();
        $tax->setAmount(0.005);

        $productVariant = new BookVariant();
        $productVariant->setStock(10);
        $productVariant->setUnitPrice(1500);
        $productVariant->setTax($tax);

        $this->expectException(EcommerceErrorException::class);
        $this->expectExceptionMessage(EcommerceErrorException::PRODUCT_STOCK_EMPTY);

        $orderItem = $this->orderItemFactory->buildOrderdItem($productVariant, 11);
        $this->assertEquals(11, $productVariant->getStock());
    }
}
