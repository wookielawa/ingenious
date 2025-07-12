<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Invoices\Domain\Exceptions\InvoiceCreationFailedException;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\ProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;
use Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use Modules\Invoices\Infrastructure\Models\EloquentProductLine;
use Throwable;

class EloquentInvoiceRepository implements InvoiceRepository
{
    /**
     * @throws Throwable
     */
    function save(Invoice $invoice): Invoice
    {
        try {
            DB::beginTransaction();

            $eloquentProductLines = [];

            /** @var ProductLine $productLine */
            foreach ($invoice->productLines as $productLine) {
                $eloquentProductLines[] = new EloquentProductLine([
                    'id'       => $productLine->uuid,
                    'name'     => $productLine->name,
                    'price'    => $productLine->price,
                    'quantity' => $productLine->quantity,
                ]);
            }

            $model = EloquentInvoice::create([
                'id'             => $invoice->uuid,
                'customer_name'  => $invoice->customerName,
                'customer_email' => $invoice->customerEmail,
                'status'         => $invoice->status->value,
            ]);

            $model->productLines()->saveMany($eloquentProductLines);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::error('Error saving invoice', [
                    'exception' => $exception,
                    'invoice'   => $invoice->toArray(),
                ],
            );

            throw new InvoiceCreationFailedException();
        }

        return $invoice;
    }
}
