<?php

namespace App\Action;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Shared\Bundle\DTO\PublishedDTOInterface;

final class ProductOutOfStockAction
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(PublishedDTOInterface $event): void
    {
        $order = $this->em->getRepository(Order::class)->find($event->orderId);
        if (!$order) throw new \Exception('Order not found: ' . $event->orderId);

        $order->setStatus('OutOfStock');
        $this->em->flush();
    }
}