<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Invoices\Application\Listeners\MarkInvoiceAsSentListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ResourceDeliveredEvent::class => [
            MarkInvoiceAsSentListener::class,
        ],
    ];
}
