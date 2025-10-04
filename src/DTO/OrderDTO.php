<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\Order;
use JsonSerializable;

final class OrderDTO implements JsonSerializable
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

    public function jsonSerialize(): array
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
