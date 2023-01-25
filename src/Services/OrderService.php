<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookVariantRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderService
{
    public const ORDER_DATA_NEEDED = [
        'orderItems',
        'user',
    ];

    private const BOOK_VARIANT = 'BookVariant';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository,
        private BookVariantRepository $bookVariantRepository,
    )
    {
    }

    public function createOrder(array $orderData)
    {
        $order = new Order();

        if (!$this-> isValidOrderData($orderData)) {
            return new JsonResponse(['The order is missing data']);
        }

        $this->buildOrder($orderData);
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

        $lastOrderNumber = $this->orderRepository->findLastOrderNumber();
        $order->setNumber($lastOrderNumber ? (int) $lastOrderNumber + 1 : 1);

        foreach ($orderData['orderItems'] as $item) {
            $order->addOrderItem($this->getOrderItem($item));
        }
    
        dd($order->getOrderItems());
    }

    public function getOrderItem(array $item): OrderItem|JsonResponse
    {
        if ($item['type'] === self::BOOK_VARIANT) {
            return $this->bookVariantRepository->find($item['id']);
        }

        return new JsonResponse('product not find');
    }
}