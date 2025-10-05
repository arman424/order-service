<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Bundle\Entity\MappedSuperclass\Product as MappedProduct;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "products")]
class Product extends MappedProduct
{
    #[ORM\Column(type: "uuid", unique: true)]
    private Uuid $productId;

    public function getProductId(): Uuid
    {
        return $this->productId;
    }

    public function setProductId(Uuid $productId): void
    {
        $this->productId = $productId;
    }
}