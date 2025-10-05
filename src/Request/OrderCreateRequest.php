<?php
declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderCreateRequest
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public $productId;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public $customerName;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(1)]
    public $quantityOrdered;
}
