<?php
declare(strict_types=1);

namespace App\Controller;

use App\Action\OrderCreateAction;
use App\Action\OrderGetAction;
use App\Action\OrderListAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderCreateAction $orderCreateAction,
        private readonly OrderListAction $orderListAction,
        private readonly OrderGetAction $orderGetAction
    ) {}

    #[Route('/orders', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return ($this->orderCreateAction)($request->getContent());
    }

    #[Route('/orders', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return ($this->orderListAction)();
    }

    #[Route('/orders/{id}', methods: ['GET'])]
    public function getOrder(string $id): JsonResponse
    {
        return ($this->orderGetAction)($id);
    }
}