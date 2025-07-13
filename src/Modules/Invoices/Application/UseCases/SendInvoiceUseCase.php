<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\UseCases;

use Illuminate\Support\Facades\Log;
use Modules\Invoices\Domain\Exceptions\InvalidInvoiceOperationException;
use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Exceptions\InvoiceUpdateFailedException;
use Modules\Invoices\Domain\Exceptions\SendingInvoiceFailedException;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Api\NotificationFacadeInterface;
use Ramsey\Uuid\Uuid;

/**
 * I decided to use NotificationFacadeInterface from the Notifications module in application layer
 * this approach doesn't break the boundaries of the application layer
 * Invoice Domain is clear of any dependencies on the Notifications module
 */
readonly class SendInvoiceUseCase
{
    public function __construct(
        private InvoiceRepository $repository,
        private NotificationFacadeInterface $notificationFacade,
    ) {}

    /**
     * @throws InvoiceNotFoundException
     * @throws SendingInvoiceFailedException
     * @throws InvoiceUpdateFailedException
     */
    public function execute(string $invoiceId): void
    {
        $invoice = $this->repository->findById($invoiceId);

        /**
         * @todo
         * The best practice would be to use queue for sending an email
         * there is already redis queue configured in the application
         * but for the sake of simplicity, I will use notification facade here
         */
        $this->notificationFacade->notify(
            new NotifyData(
                resourceId: Uuid::fromString($invoiceId),
                toEmail: $invoice->customerEmail,
                subject: 'Invoice #' . $invoiceId . ' is ready',
                message: 'Your invoice is ready. Please check it at your account.'
            )
        );

        try {
            $invoice->send();
        } catch (InvalidInvoiceOperationException $exception) {
            Log::error('Failed to send invoice', [
                'invoiceId' => $invoiceId,
                'exception' => $exception->getMessage(),
            ]);

            throw new SendingInvoiceFailedException();
        }

        $this->repository->update($invoice);
    }
}
