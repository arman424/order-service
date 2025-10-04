<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\OrderDTO;
use Shared\Bundle\RabbitMQ\PublisherInterface;

class OrderPublisher
{
    public function __construct(private readonly PublisherInterface $publisher) {}

    public function publish(OrderDTO $dto): void
    {
        $this->publisher->publish($dto);
    }
}
