<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Action\ProductReservationAction;
use Shared\Bundle\Messaging\ProductReservedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProductReservedHandler
{
    public function __construct(
        private readonly ProductReservationAction $productReservationAction,
    ) {}

    public function __invoke(ProductReservedMessage $message): void
    {
        $event = $message->orderReservationDTO;
        ($this->productReservationAction)($event);
    }
}

