<?php

namespace App\Controller\Invoice;

use App\Entity\Order;
use App\Exceptions\EcommerceErrorException;
use App\Repository\OrderRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

#[AsController]
class InvoiceController extends AbstractController
{

    public function __construct(
        private Security $security,
        private OrderRepository $orderRepository
        )
    {
    }

    public function __invoke($id)
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser) {
            throw new UnauthorizedHttpException(404);
        }

        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw new EcommerceErrorException("order not found");
        }

        if ($order->getUser() !== $currentUser) {
            throw new UnauthorizedHttpException(404);
        }

        return $this->file($order->getInvoice());
    }
}