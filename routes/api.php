<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__.'/../src/Modules/Notifications/Presentation/routes.php';
    require __DIR__.'/../src/Modules/Invoices/Presentation/routes.php';
});
