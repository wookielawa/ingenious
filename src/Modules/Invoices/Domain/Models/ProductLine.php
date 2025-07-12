<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Models;

use Modules\Common\Domain\Entity;

class ProductLine extends Entity
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $name,
        public readonly float $price,
        public readonly int $quantity,
    ) {}

    public function total(): float
    {
        return $this->price * $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'uuid'     => $this->uuid,
            'name'     => $this->name,
            'price'    => $this->price,
            'quantity' => $this->quantity,
            'total'    => $this->price * $this->quantity,
        ];
    }
}
