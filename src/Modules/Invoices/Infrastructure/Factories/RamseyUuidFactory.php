<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Factories;

use Modules\Invoices\Domain\Factories\UuidFactory;
use Ramsey\Uuid\Uuid;

class RamseyUuidFactory implements UuidFactory
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
