<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Factories;

use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\ProductLine;
use Modules\Invoices\Domain\Models\ValueObjects\ProductLines;

readonly class DraftInvoiceFactory implements InvoiceFactory
{
    public function __construct(
        private UuidFactory $uuidFactory,
    ) {}

    public function create(array $inputData): Invoice
    {
        $productLines = $this->createProductLines($inputData['product_lines'] ??
            []);

        return new Invoice(
            uuid: $this->uuidFactory->generate(),
            customerName: $inputData['customer_name'],
            customerEmail: $inputData['customer_email'],
            status: StatusEnum::Draft,
            productLines: new ProductLines($productLines),
        );
    }

    /**
     * Later, if needed, this method can be moved to a ProductLineFactory
     */
    private function createProductLines(array $inputProductLines): array
    {
        $productLines = [];

        foreach ($inputProductLines as $productLine) {
            $productLines[] = new ProductLine(
                uuid: $this->uuidFactory->generate(),
                name: $productLine['name'],
                price: $productLine['price'],
                quantity: $productLine['quantity'],
            );
        }

        return $productLines;
    }
}
