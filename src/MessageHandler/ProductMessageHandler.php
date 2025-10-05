<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Action\ProductCreatedAction;
use Shared\Bundle\Messaging\ProductMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProductMessageHandler
{
    public function __construct(private ProductCreatedAction $productCreatedAction) {}

    public function __invoke(ProductMessage $message): void
    {
        $event = $message->productDTO;
        ($this->productCreatedAction)($event);
    }
}