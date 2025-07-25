<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Models;

use Modules\Common\Domain\AggregateRoot;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Exceptions\InvalidInvoiceOperationException;
use Modules\Invoices\Domain\Models\ValueObjects\ProductLines;

class Invoice extends AggregateRoot
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $customerName,
        public readonly string $customerEmail,
        public StatusEnum $status,
        public readonly ProductLines $productLines,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {}

    public function total(): float
    {
        return $this->productLines ? $this->productLines->total() : 0;
    }

    public function toArray(): array
    {;
        return [
            'uuid' => $this->uuid,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'status' => $this->status->value,
            'product_lines' => $this->productLines->toArray() ?? [],
            'total' => $this->total(),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    /**
     * @throws InvalidInvoiceOperationException
     */
    public function send(): void
    {
        if (!$this->status->isDraft()) {
            throw new InvalidInvoiceOperationException('Invoice status must be draft');
        }

        if (!$this->productLines->hasValidItems()) {
            throw new InvalidInvoiceOperationException(
                'Cannot send invoice with invalid or empty product lines.'
            );
        }

        $this->status = StatusEnum::Sending;
    }

    /**
     * @throws InvalidInvoiceOperationException
     */
    public function markAsSent(): void
    {
        if (!$this->status->isSending()) {
            throw new InvalidInvoiceOperationException('Invoice status must be sending');
        }

        $this->status = StatusEnum::SentToClient;
    }
}
