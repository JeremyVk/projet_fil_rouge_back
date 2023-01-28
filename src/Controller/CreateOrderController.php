<?php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Order;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class CreateOrderController extends AbstractController
{
    private $bookPublishingHandler;


    public function __invoke(Order $order)
    {
        // dd($order);
        return $order;
    }
}