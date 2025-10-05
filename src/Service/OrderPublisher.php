<?php
declare(strict_types=1);

namespace App\Service;

use Shared\Bundle\DTO\PublishedDTOInterface;
use Shared\Bundle\Messaging\OrderMessage;
use Shared\Bundle\Publisher\Publisher;

class OrderPublisher extends Publisher
{
    public function publish(PublishedDTOInterface $publishedDTO): void
    {
        $this->messageBus->dispatch(new OrderMessage($publishedDTO));
    }
}
