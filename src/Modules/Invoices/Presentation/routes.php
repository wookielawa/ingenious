<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoices\Presentation\Http\CreateInvoiceController;
use Modules\Invoices\Presentation\Http\SendInvoiceController;

Route::post('invoices', CreateInvoiceController::class)->name('invoices.create');
Route::post('invoices/{invoiceId}/send', SendInvoiceController::class)->name('invoices.send');
