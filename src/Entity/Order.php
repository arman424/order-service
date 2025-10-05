<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'uuid')]
    private Uuid $productId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $productName;

    #[ORM\Column(type: 'integer')]
    private int $productPrice;

    #[ORM\Column(type: 'integer')]
    private int $productAvailableQuantityAtOrder;

    #[ORM\Column(type: 'string', length: 255)]
    private string $customerName;

    #[ORM\Column(type: 'integer')]
    private int $quantityOrdered;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getProductId(): Uuid
    {
        return $this->productId;
    }

    public function setProductId(Uuid $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }
    public function setProductName(string $name): void
    {
        $this->productName = $name;
    }

    public function getProductPrice(): int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $price): void
    {
        $this->productPrice = $price;
    }

    public function getProductAvailableQuantityAtOrder(): int
    {
        return $this->productAvailableQuantityAtOrder;
    }

    public function setProductAvailableQuantityAtOrder(int $quantity): void
    {
        $this->productAvailableQuantityAtOrder = $quantity;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function getQuantityOrdered(): int
    {
        return $this->quantityOrdered;
    }

    public function setQuantityOrdered(int $quantityOrdered): void
    {
        $this->quantityOrdered = $quantityOrdered;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'orderId' => (string)$this->id,
            'product' => [
                'id' => (string)$this->productId,
                'name' => $this->productName,
                'price' => $this->productPrice,
                'quantity' => $this->productAvailableQuantityAtOrder,
            ],
            'customerName' => $this->customerName,
            'quantityOrdered' => $this->quantityOrdered,
            'orderStatus' => $this->status,
        ];
    }
}
