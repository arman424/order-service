<?php
declare(strict_types=1);

namespace App\Action;

use App\DTO\OrderDTO;
use App\Request\OrderCreateRequest;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\OrderPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class OrderCreateAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private OrderPublisher $publisher
    ) {}

    /**
     * @param string $orderData JSON string from request
     * @return Order
     */
    public function __invoke(string $orderData): Order
    {
        $orderData = json_decode($orderData, true);
        $request = $this->mapRequest($orderData);

        $errors = $this->validator->validate($request);
        if (count($errors) > 0) {
            throw new ValidationFailedException($request, $errors);
        }

        // 1. Find product locally
        $product = $this->em->getRepository(Product::class)->find($request->productId);
        if (!$product) {
            throw new \InvalidArgumentException('Product not found');
        }

        // 2. Check stock
        if ($product->getQuantity() < $request->quantityOrdered) {
            throw new \InvalidArgumentException('Insufficient product quantity');
        }

        // 3. Decrement stock
        $product->setQuantity($product->getQuantity() - $request->quantityOrdered);

        // 4. Create order entity
        $order = new Order(Uuid::v4());
        $order->setProductId($product->getId());
        $order->setProductName($product->getName());
        $order->setProductPrice((float) $product->getPrice());
        $order->setProductAvailableQuantityAtOrder($product->getQuantity() + $request->quantityOrdered); // snapshot before deduction
        $order->setCustomerName($request->customerName);
        $order->setQuantityOrdered($request->quantityOrdered);
        $order->setStatus('Processing');

        // 5. Save
        $this->em->persist($product);
        $this->em->persist($order);
        $this->em->flush();

        // 6. Publish event
        $dto = OrderDTO::fromEntity($order);
        $this->publisher->publish($dto);

        return $order;
    }

    private function mapRequest(array $orderData): OrderCreateRequest
    {
        $request = new OrderCreateRequest();
        $request->productId = $orderData['productId'] ?? null;
        $request->customerName = $orderData['customerName'] ?? null;
        $request->quantityOrdered = $orderData['quantityOrdered'] ?? null;

        return $request;
    }
}