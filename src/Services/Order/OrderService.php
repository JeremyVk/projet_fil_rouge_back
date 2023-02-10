<?php

namespace App\Services\Order;

use Exception;
use App\Entity\Order;
use DateTimeImmutable;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\EcommerceErrorException;
use App\Repository\ProductVariantRepository;
use Symfony\Component\Security\Core\Security;
use App\Services\Order\OrderItem\OrderItemFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Abstract\OrderItem\BaseOrderItemInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OrderService
{
    public const ORDER_DATA_NEEDED = [
        'orderItems',
        'user',
        'shippingAmount',
        'shippingAddress'
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository,
        private ProductVariantRepository $productVariantRepository,
        private OrderItemFactory $orderItemFactory,
        private UserRepository $userRepository,
        private Security $security,
        private AddressRepository $addressRepository,
    )
    {
    }

    public function createOrder(array $orderData)
    {
        if (!$this-> isValidOrderData($orderData)) {
            throw new Exception('The order is missing data');
        }

        try {
            $order = $this->buildOrder($orderData);
        } catch( EcommerceErrorException $e) {
            return new JsonResponse($e->getMessage(), 500);
        }

        return $order;
    }

    public function isValidOrderData(array $orderData): bool
    {
        foreach (self::ORDER_DATA_NEEDED as $fieldNeeded) {
            if (!isset($orderData[$fieldNeeded]) || empty($orderData[$fieldNeeded])) {
                return false;
            }
        }
        return true;
    }

    public function buildOrder($orderData): Order
    {
        $order = new Order;
        $user = $this->security->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException("user not connected or user not found");
        }

        $order->setUser($user);

        $lastOrderRegistered = $this->orderRepository->findLastOrderNumber();
        $order->setNumber($lastOrderRegistered ? (int) $lastOrderRegistered->getNumber() + 1 : 1);

        $shippingAddress = $this->addressRepository->find($orderData['shippingAddress']);

        if(!$shippingAddress) {
            throw new Exception("address not found");
        }

        foreach ($orderData['orderItems'] as $item) {
            $order->addOrderItem($this->getOrderItem($item));
        }

        $this->entityManager->flush();
        
        $order->setShippingAddress($shippingAddress);
        $order->setShippingAmount($orderData['shippingAmount']);
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setAmount($this->calculateOrderTotalAmount($order));

        return $order;
    }

    public function getOrderItem(array $item): BaseOrderItemInterface
    {
        $productVariant = $this->productVariantRepository->find($item['id']);
        if (!$productVariant) {
            throw new Exception("product variant with id: " . $item['id'] . " not found");
        }

        return $this->orderItemFactory->buildOrderdItem($productVariant, $item['quantity']);
    }

    public function calculateOrderTotalAmount(Order $order): int
    {
        $orderTotalAmount = 0;

        foreach($order->getOrderItems() as $item) {
            $orderTotalAmount += $item->getPrice() * $item->getQuantity();
        }
        $orderTotalAmount += $order->getShippingAmount();

        return $orderTotalAmount;
    }
}