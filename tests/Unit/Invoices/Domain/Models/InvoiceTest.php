<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Domain\Models;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Models\ValueObjects\ProductLines;
use Modules\Invoices\Domain\Exceptions\InvalidInvoiceOperationException;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    private function createProductLinesMock(float $total = 100.0, bool $hasValidItems = true): ProductLines
    {
        $mock = $this->createMock(ProductLines::class);
        $mock->method('total')->willReturn($total);
        $mock->method('hasValidItems')->willReturn($hasValidItems);
        $mock->method('toArray')->willReturn([['name' => 'Test', 'price' => $total]]);
        return $mock;
    }

    public function testTotalReturnsProductLinesTotal()
    {
        $invoice = new Invoice(
            uuid: 'uuid-1',
            customerName: 'John Doe',
            customerEmail: 'john@example.com',
            status: StatusEnum::Draft,
            productLines: $this->createProductLinesMock(123.45)
        );
        $this->assertSame(123.45, $invoice->total());
    }

    public function testToArrayReturnsExpectedArray()
    {
        $productLines = $this->createProductLinesMock(50.0);
        $invoice = new Invoice(
            uuid: 'uuid-2',
            customerName: 'Jane Doe',
            customerEmail: 'jane@example.com',
            status: StatusEnum::Draft,
            productLines: $productLines,
            createdAt: '2024-06-01',
            updatedAt: '2024-06-02'
        );
        $array = $invoice->toArray();
        $this->assertSame('uuid-2', $array['uuid']);
        $this->assertSame('Jane Doe', $array['customer_name']);
        $this->assertSame('jane@example.com', $array['customer_email']);
        $this->assertSame(StatusEnum::Draft->value, $array['status']);
        $this->assertSame([['name' => 'Test', 'price' => 50.0]], $array['product_lines']);
        $this->assertSame(50.0, $array['total']);
        $this->assertSame('2024-06-01', $array['created_at']);
        $this->assertSame('2024-06-02', $array['updated_at']);
    }

    public function testSendChangesStatusToSending()
    {
        $invoice = new Invoice(
            uuid: 'uuid-3',
            customerName: 'Client',
            customerEmail: 'client@example.com',
            status: StatusEnum::Draft,
            productLines: $this->createProductLinesMock()
        );
        $invoice->send();
        $this->assertSame(StatusEnum::Sending, $invoice->status);
    }

    public function testSendThrowsIfNotDraft()
    {
        $invoice = new Invoice(
            uuid: 'uuid-4',
            customerName: 'Client',
            customerEmail: 'client@example.com',
            status: StatusEnum::SentToClient,
            productLines: $this->createProductLinesMock()
        );
        $this->expectException(InvalidInvoiceOperationException::class);
        $invoice->send();
    }

    public function testSendThrowsIfProductLinesInvalid()
    {
        $invoice = new Invoice(
            uuid: 'uuid-5',
            customerName: 'Client',
            customerEmail: 'client@example.com',
            status: StatusEnum::Draft,
            productLines: $this->createProductLinesMock(hasValidItems: false)
        );
        $this->expectException(InvalidInvoiceOperationException::class);
        $invoice->send();
    }

    public function testMarkAsSentChangesStatusToSentToClient()
    {
        $invoice = new Invoice(
            uuid: 'uuid-6',
            customerName: 'Client',
            customerEmail: 'client@example.com',
            status: StatusEnum::Sending,
            productLines: $this->createProductLinesMock()
        );
        $invoice->markAsSent();
        $this->assertSame(StatusEnum::SentToClient, $invoice->status);
    }

    public function testMarkAsSentThrowsIfNotSending()
    {
        $invoice = new Invoice(
            uuid: 'uuid-7',
            customerName: 'Client',
            customerEmail: 'client@example.com',
            status: StatusEnum::Draft,
            productLines: $this->createProductLinesMock()
        );
        $this->expectException(InvalidInvoiceOperationException::class);
        $invoice->markAsSent();
    }
}
