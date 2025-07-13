<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\UseCases;

use Illuminate\Support\Facades\Log;
use Modules\Invoices\Domain\Exceptions\InvalidInvoiceOperationException;
use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Exceptions\InvoiceUpdateFailedException;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;

readonly class MarkInvoiceAsSentUseCase
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
    ) {}

    /**
     * @throws InvoiceNotFoundException
     * @throws InvalidInvoiceOperationException
     * @throws InvoiceUpdateFailedException
     */
    public function execute(string $invoiceUuid): void
    {
        $invoice = $this->invoiceRepository->findById($invoiceUuid);

        try {
            $invoice->markAsSent();
        } catch (InvalidInvoiceOperationException $exception) {
            Log::error('Failed to mark invoice as sent', [
                'invoice_uuid' => $invoiceUuid,
                'exception' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $this->invoiceRepository->update($invoice);
    }
}
