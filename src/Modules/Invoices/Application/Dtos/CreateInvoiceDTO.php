<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Dtos;

readonly class CreateInvoiceDTO
{
    public function __construct(
        public string $customerName,
        public string $customerEmail,
        public array $productLines = [],
    ) {}

    public static function fromArray(array $data): CreateInvoiceDTO
    {
        return new self(
            customerName: $data['customer_name'],
            customerEmail: $data['customer_email'],
            productLines: $data['product_lines'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'customer_name'  => $this->customerName,
            'customer_email' => $this->customerEmail,
            'product_lines'  => $this->productLines,
        ];
    }
}
