<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Invoices\Domain\Factories\DraftInvoiceFactory;
use Modules\Invoices\Domain\Factories\InvoiceFactory;
use Modules\Invoices\Domain\Factories\UuidFactory;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;
use Modules\Invoices\Infrastructure\Factories\RamseyUuidFactory;
use Modules\Invoices\Infrastructure\Repositories\EloquentInvoiceRepository;

final class InvoiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(
            InvoiceRepository::class,
            EloquentInvoiceRepository::class,
        );

        $this->app->bind(
            InvoiceFactory::class,
            DraftInvoiceFactory::class
        );

        $this->app->bind(
            UuidFactory::class,
            RamseyUuidFactory::class
        );
    }

    public function provides(): array
    {
        return [
            InvoiceRepository::class,
            InvoiceFactory::class,
        ];
    }
}
