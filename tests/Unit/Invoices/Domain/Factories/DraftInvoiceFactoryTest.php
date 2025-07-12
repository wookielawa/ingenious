<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Domain\Factories;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Factories\DraftInvoiceFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DraftInvoiceFactoryTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_creates_a_draft_invoice_with_product_lines(): void
    {
        $data = [
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'product_lines' => [
                [
                    'name' => $this->faker->word(),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'quantity' => $this->faker->numberBetween(1, 10),
                ],
                [
                    'name' => $this->faker->word(),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'quantity' => $this->faker->numberBetween(1, 10),
                ],
            ],
        ];

        $invoice = app(DraftInvoiceFactory::class)->create($data);

        $this->assertSame($data['customer_name'], $invoice->customerName);
        $this->assertSame($data['customer_email'], $invoice->customerEmail);
        $this->assertSame(StatusEnum::Draft, $invoice->status);
        $this->assertCount(2, $invoice->productLines);
    }

    #[Test]
    public function it_can_create_a_draft_invoice_without_product_lines(): void
    {
        $data = [
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
        ];

        $invoice = app(DraftInvoiceFactory::class)->create($data);

        $this->assertSame($data['customer_name'], $invoice->customerName);
        $this->assertSame($data['customer_email'], $invoice->customerEmail);
        $this->assertSame(StatusEnum::Draft, $invoice->status);
        $this->assertCount(0, $invoice->productLines);
    }
}
