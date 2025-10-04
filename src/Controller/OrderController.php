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
use Symfony\Component\Validator\Exception\ValidationFailedException;

class OrderController extends AbstractController
{
    public function __construct(
        private OrderCreateAction $orderCreateAction,
        private OrderGetAction $orderGetAction,
        private OrderListAction $orderListAction
    ) {}

    #[Route('/orders', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $order = ($this->orderCreateAction)($request->getContent());
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], 422);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json($order->toArray(), 201);
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