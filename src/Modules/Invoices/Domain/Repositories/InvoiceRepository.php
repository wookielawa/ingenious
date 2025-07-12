<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Repositories;

use Modules\Invoices\Domain\Exceptions\InvoiceCreationFailedException;
use Modules\Invoices\Domain\Models\Invoice;

interface InvoiceRepository
{
    /**
     * @throws InvoiceCreationFailedException
     */
    function save(Invoice $invoice): Invoice;
}
