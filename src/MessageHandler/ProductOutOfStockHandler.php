<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Action\ProductOutOfStockAction;
use Shared\Bundle\Messaging\ProductOutOfStockMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProductOutOfStockHandler
{
    public function __construct(
        private readonly ProductOutOfStockAction $productOutOfStockAction
    ) {}

    public function __invoke(ProductOutOfStockMessage $message): void
    {
        $event = $message->productOutOfStockDTO;
        ($this->productOutOfStockAction)($event);
    }
}