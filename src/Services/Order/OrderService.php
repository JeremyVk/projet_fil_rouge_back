<?php

namespace App\Services\Order;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Abstract\OrderItem\BaseOrderItemInterface;
use App\Repository\UserRepository;
use App\Services\Order\OrderItem\OrderItemFactory;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Security\Core\Security;

class OrderService
{
    public const ORDER_DATA_NEEDED = [
        'orderItems',
        'user',
        'shippingAmout',
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository,
        private ProductVariantRepository $productVariantRepository,
        private OrderItemFactory $orderItemFactory,
        private UserRepository $userRepository,
        private Security $security,
    )
    {
    }

    public function createOrder(array $orderData)
    {
        if (!$this-> isValidOrderData($orderData)) {
            return new JsonResponse(['The order is missing data']);
        }

        return $this->buildOrder($orderData);
    }

    public function isValidOrderData(array $orderData): bool
    {
        foreach (self::ORDER_DATA_NEEDED as $fieldNeeded) {
            if (!isset($orderData[$fieldNeeded]) || empty($orderData[$fieldNeeded])) {
                return false;
            }

            return true;
        }
    }

    public function buildOrder($orderData): Order
    {
        $order = new Order;
        $user = $this->userRepository->find($orderData['user']['id']);

        if (!$user) {
            throw new Exception("user not connected or user not found");
        }

        $order->setUser($user);

        $lastOrderRegistered = $this->orderRepository->findLastOrderNumber();
        $order->setNumber($lastOrderRegistered ? (int) $lastOrderRegistered->getNumber() + 1 : 1);

        foreach ($orderData['orderItems'] as $item) {
            $order->addOrderItem($this->getOrderItem($item));
        }

        $this->entityManager->flush();
        
        $order->setShippingAmount($orderData['shippingAmount']);
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setAmount($this->calculateOrderTotalAmount($order));

        return $order;
    }

    public function getOrderItem(array $item): BaseOrderItemInterface | JsonResponse
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