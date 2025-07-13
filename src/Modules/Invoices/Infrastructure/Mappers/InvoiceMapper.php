<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Mappers;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\ProductLine;
use Modules\Invoices\Domain\Models\ValueObjects\ProductLines;
use Modules\Invoices\Infrastructure\Models\EloquentInvoice;

class InvoiceMapper
{
    public static function fromEloquentModel(EloquentInvoice $eloquentInvoice): Invoice
    {
        $productLines = [];

        foreach ($eloquentInvoice->productLines as $line) {
            $productLines[] = new ProductLine(
                uuid: $line->id,
                name: $line->name,
                price: $line->price,
                quantity: $line->quantity
            );
        }

        return new Invoice(
            uuid: $eloquentInvoice->id,
            customerName: $eloquentInvoice->customer_name,
            customerEmail: $eloquentInvoice->customer_email,
            status: $eloquentInvoice->status,
            productLines: new ProductLines($productLines)
        );
    }
}
