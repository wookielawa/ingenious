<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Invoices\Domain\Exceptions\InvoiceCreationFailedException;
use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Exceptions\InvoiceUpdateFailedException;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\ProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;
use Modules\Invoices\Infrastructure\Mappers\InvoiceMapper;
use Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use Modules\Invoices\Infrastructure\Models\EloquentProductLine;
use Throwable;

class EloquentInvoiceRepository implements InvoiceRepository
{
    /**
     * @throws InvoiceCreationFailedException
     */
    function create(Invoice $invoice): Invoice
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
                'exception' => $exception->getMessage(),
                'invoice'   => $invoice->toArray(),
            ]);

            throw new InvoiceCreationFailedException();
        }

        return $invoice;
    }

    function findById(string $invoiceId): Invoice
    {
        try {
            $model = EloquentInvoice::with('productLines')
                ->where('id', $invoiceId)
                ->firstOrFail();

            return InvoiceMapper::fromEloquentModel($model);
        } catch (ModelNotFoundException) {
            Log::error('Invoice not found', ['invoiceId' => $invoiceId]);

            throw new InvoiceNotFoundException();
        } catch (Throwable $exception) {
            Log::error('Error finding invoice', [
                'exception' => $exception->getMessage(),
                'invoiceId' => $invoiceId,
            ]);

            throw new InvoiceNotFoundException();
        }
    }

    /**
     * @throws InvoiceNotFoundException|InvoiceUpdateFailedException
     */
    function update(Invoice $invoice): Invoice
    {
        try {
            EloquentInvoice::findOrFail($invoice->uuid)->update($invoice->toArray());

            return $invoice;
        } catch (ModelNotFoundException) {
            Log::error(
                'Invoice not found for update', [
                'invoiceId' => $invoice->uuid,
            ]);

            throw new InvoiceNotFoundException();
        } catch (Throwable $exception) {
            Log::error('Error updating invoice', [
                'exception' => $exception->getMessage(),
                'invoice'   => $invoice->toArray(),
            ]);

            throw new InvoiceUpdateFailedException();
        }
    }
}
