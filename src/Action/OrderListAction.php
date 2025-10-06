<?php
declare(strict_types=1);

namespace App\Action;

use App\DTO\OrderDTO;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class OrderListAction
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(): JsonResponse
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        $data = array_map(fn($p) => OrderDTO::fromEntity($p), $orders);

        return new JsonResponse(['data' => $data]);
    }
}