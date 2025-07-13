<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Repositories;

use Modules\Invoices\Domain\Exceptions\InvoiceCreationFailedException;
use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Exceptions\InvoiceUpdateFailedException;
use Modules\Invoices\Domain\Models\Invoice;

interface InvoiceRepository
{
    /**
     * @throws InvoiceCreationFailedException
     */
    function create(Invoice $invoice): Invoice;

    /**
     * @throws InvoiceNotFoundException
     */
    function findById(string $invoiceId): Invoice;

    /**
     * @throws InvoiceNotFoundException|InvoiceUpdateFailedException
     */
    function update(Invoice $invoice): Invoice;
}
