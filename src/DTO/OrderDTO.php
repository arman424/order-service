<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\Order;

final class OrderDTO
{
    public string $orderId;
    public array $product;
    public string $customerName;
    public int $quantityOrdered;
    public string $orderStatus;

    public static function fromEntity(Order $order): self
    {
        $dto = new self();

        $dto->orderId = (string)$order->getId();
        $dto->product = [
            'id' => (string)$order->getProductId(),
            'name' => $order->getProductName(),
            'price' => $order->getProductPrice(),
            'quantity' => $order->getProductAvailableQuantityAtOrder(),
        ];
        $dto->customerName = $order->getCustomerName();
        $dto->quantityOrdered = $order->getQuantityOrdered();
        $dto->orderStatus = $order->getStatus();

        return $dto;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->product['id'];
    }

    /**
     * @return int
     */
    public function getQuantityOrdered(): int
    {
        return $this->quantityOrdered;
    }

    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'product' => $this->product,
            'customerName' => $this->customerName,
            'quantityOrdered' => $this->quantityOrdered,
            'orderStatus' => $this->orderStatus,
        ];
    }
}
