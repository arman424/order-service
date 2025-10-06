<?php
declare(strict_types=1);

namespace App\Action;

use App\Request\OrderCreateRequest;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\OrderPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Shared\Bundle\DTO\OrderReservationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class OrderCreateAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface $validator,
        private readonly OrderPublisher $publisher
    ) {}

    /**
     * @param string $orderData
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(string $orderData): JsonResponse
    {
        try {
            $orderData = json_decode($orderData, true);
            $request = $this->mapRequest($orderData);

            $errors = $this->validator->validate($request);
            if (count($errors) > 0) {
                throw new ValidationFailedException($request, $errors);
            }

            $product = $this->em->getRepository(Product::class)->findOneBy(['productId' => $request->productId]);
            if (!$product) {
                throw new InvalidArgumentException('Product not found');
            }

            if ($product->getQuantity() < $request->quantityOrdered) {
                throw new InvalidArgumentException('Insufficient product quantity');
            }

            $order = new Order(Uuid::v4());
            $order->setProductId($product->getProductId());
            $order->setProductName($product->getName());

            // Price stored as integer to avoid floating-point precision issues.
            // TODO: Consider using a dedicated Money object for more robust handling.

            $order->setProductPrice((int) $product->getPrice());
            $order->setProductAvailableQuantityAtOrder($product->getQuantity());
            $order->setCustomerName($request->customerName);
            $order->setQuantityOrdered($request->quantityOrdered);
            $order->setStatus('Processing');

            $this->em->persist($order);
            $this->em->flush();

            $this->publisher->publish(OrderReservationDTO::init([
                'orderId' => $order->getId(),
                'productId' => $order->getProductId(),
                'quantity' => $order->getQuantityOrdered()
            ]));

            return new JsonResponse($order->toArray(), 201);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 422);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return new JsonResponse(['error' => 'Internal Server Error'], 500);
        }
    }

    private function mapRequest(array $orderData): OrderCreateRequest
    {
        $request = new OrderCreateRequest();
        $request->productId = $orderData['productId'];
        $request->quantityOrdered = $orderData['quantityOrdered'];
        $request->customerName = $orderData['customerName'] ?? null;

        return $request;
    }
}