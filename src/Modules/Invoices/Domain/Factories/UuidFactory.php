<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Factories;

interface UuidFactory {
    public function generate(): string;
}
