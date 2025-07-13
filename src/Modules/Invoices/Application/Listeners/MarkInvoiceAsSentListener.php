<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Invoices\Application\UseCases\MarkInvoiceAsSentUseCase;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Throwable;

readonly class MarkInvoiceAsSentListener
{
    public function __construct(
        private MarkInvoiceAsSentUseCase $useCase,
    ) {}

    public function handle(ResourceDeliveredEvent $event): void
    {
        try {
            $this->useCase->execute($event->resourceId->toString());
        } catch (Throwable $exception) {
            Log::error('Failed to mark invoice as sent', [
                'invoice_uuid' => $event->resourceId->toString(),
                'exception' => $exception->getMessage(),
            ]);
        }
    }
}
