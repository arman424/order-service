<?php
declare(strict_types=1);

namespace App\Action;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Shared\Bundle\DTO\PublishedDTOInterface;

final class ProductReservationAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function __invoke(PublishedDTOInterface $event): void
    {
        $order = $this->em->getRepository(Order::class)->find($event->orderId);
        if (!$order) {
            throw new \Exception('Order not found: ' . $event->orderId);
        }

        $product = $this->em->getRepository(Product::class)->findOneBy([
            'productId' => $event->productId
        ]);

        if (!$product) {
            throw new \Exception('Product not found in order service: ' . $event->productId);
        }

        $product->setQuantity($product->getQuantity() - $event->quantity);
        $this->em->persist($product);

        $order->setStatus('Reserved');
        $this->em->flush();
    }
}