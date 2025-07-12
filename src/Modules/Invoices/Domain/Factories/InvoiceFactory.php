<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Factories;

use Modules\Invoices\Domain\Models\Invoice;

interface InvoiceFactory
{
    public function create(array $inputData): Invoice;
}
