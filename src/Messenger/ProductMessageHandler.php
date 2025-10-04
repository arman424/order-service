<?php
declare(strict_types=1);

namespace App\Messenger;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Shared\Bundle\Messaging\ProductMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class ProductMessageHandler
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(ProductMessage $message): void
    {
        $dto = $message->productDTO;

        $product = $this->em->getRepository(Product::class)->find($dto->id);
        if (!$product) {
            $product = new Product();
            $product->setId(Uuid::v4());
        }

        $product->setProductId($dto->id);
        $product->setName($dto->name);
        $product->setPrice((int) $dto->price);
        $product->setQuantity($dto->quantity);

        $this->em->persist($product);
        $this->em->flush();
    }
}