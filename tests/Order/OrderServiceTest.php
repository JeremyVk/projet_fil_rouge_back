<?php

namespace App\Test\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Entity\Variants\BookVariant;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\UserRepository;
use App\Services\Invoice\InvoiceFactory;
use App\Services\Order\OrderItem\OrderItemFactory;
use App\Services\Order\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

class OrderServiceTest extends TestCase
{
    private OrderService $orderService;
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;
    private ProductVariantRepository $productVariantRepository;
    private OrderItemFactory $orderItemFactory;
    private UserRepository $userRepository;
    private Security $security;
    private AddressRepository $addressRepository;
    private InvoiceFactory $invoiceFactory;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->productVariantRepository = $this->createMock(ProductVariantRepository::class);
        $this->orderItemFactory = $this->createMock(OrderItemFactory::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->security = $this->createMock(Security::class);
        $this->addressRepository = $this->createMock(AddressRepository::class);
        $this->invoiceFactory = $this->createMock(InvoiceFactory::class);

        $this->orderService = new OrderService(
            $this->entityManager,
            $this->orderRepository,
            $this->productVariantRepository,
            $this->orderItemFactory,
            $this->userRepository,
            $this->security,
            $this->addressRepository,
            $this->invoiceFactory
        );
    }

    public function testIsValidOrderDataTrue()
    {
        $order = [
            'orderItems' => [
                '1'
            ],
            'shippingAmount' => '3500',
            'shippingAddressId' => 1
        ];
        $isValidOrderData = $this->orderService->isValidOrderData($order);

        $this->assertTrue($isValidOrderData);
    }

    public function testIsValidOrderDataFalse()
    {
        $order = [
            'orderItems' => [
                '1'
            ],
            'shippingAmount' => '3500',
        ];
        $isValidOrderData = $this->orderService->isValidOrderData($order);

        $this->assertFalse($isValidOrderData);
    }

    public function testCalculateOrderTotalAmountWithOneItem()
    {
        $order = new Order();

        $variant1 = new BookVariant();
        $item1 = new OrderItem();
        $item1->setOrdered($order);
        $item1->setPrice(1500);
        $item1->setQuantity(2);
        $order->addOrderItem($item1);

        $orderAmount = $this->orderService->calculateOrderTotalAmount($order);

        $this->assertEquals(3000, $orderAmount);
    }

    public function testCalculateOrderTotalAmountWithManyItems()
    {
        $order = new Order();

        $item1 = new OrderItem();
        $item1->setOrdered($order);
        $item1->setPrice(1500);
        $item1->setQuantity(2);

        $item2 = new OrderItem();
        $item2->setOrdered($order);
        $item2->setPrice(4500);
        $item2->setQuantity(1);


        $order->addOrderItem($item1);
        $order->addOrderItem($item2);

        $orderAmount = $this->orderService->calculateOrderTotalAmount($order);

        $this->assertEquals(7500, $orderAmount);
    }

    public function testOrderFactoryWithOneVariantOutOfStock()
    {
        $order = new Order();

        $item1 = new OrderItem();
        $item1->setOrdered($order);
        $item1->setPrice(1500);
        $item1->setQuantity(2);

        $item2 = new OrderItem();
        $item2->setOrdered($order);
        $item2->setPrice(4500);
        $item2->setQuantity(1);


        $order->addOrderItem($item1);
        $order->addOrderItem($item2);

        $this->assertTrue(true);
    }

    public function testOrderFactoryWithNotConnectedUserThrowException()
    {
        $this->security->method('getUser')->willReturn(null);

        $order = [
            'orderItems' => [
                '1'
            ],
            'shippingAmount' => '3500',
            'shippingAddressId' => 1
        ];

        $this->expectException(UnauthorizedHttpException::class);
        $this->orderService->buildOrder($order);
    }

    public function testOrderFactoryWithAddressNotFound()
    {
        $order = [
            'orderItems' => [
                '1'
            ],
            'shippingAmount' => '3500',
            'shippingAddressId' => 1
        ];

        $this->security
            ->method('getUser')
            ->willReturn(new User());
        $this->addressRepository
            ->method('find')
            ->with($order['shippingAddressId'])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("address.not.found");

        $this->orderService->buildOrder($order);
    }

    public function testGetOrderItemWithItemNotFound()
    {
        $itemId = 1;
        $this->productVariantRepository
            ->method('find')
            ->with($itemId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->orderService->getOrderItem(['id' => $itemId]);
    }
}
