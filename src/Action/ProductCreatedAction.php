<?php
declare(strict_types=1);

namespace App\Action;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Shared\Bundle\DTO\PublishedDTOInterface;
use Symfony\Component\Uid\Uuid;

final class ProductCreatedAction
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(PublishedDTOInterface $event): void
    {
        $product = $this->em->getRepository(Product::class)->find($event->id);
        if (!$product) {
            $product = new Product();
            $product->setId(Uuid::v4());
        }

        $product->setProductId($event->id);
        $product->setName($event->name);
        $product->setPrice($event->price);
        $product->setQuantity($event->quantity);

        $this->em->persist($product);
        $this->em->flush();
    }
}