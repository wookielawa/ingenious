<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Presentation\Http\NotificationController;
use Ramsey\Uuid\Validator\GenericValidator;

Route::pattern('action', '^[a-zA-Z]+$');
Route::pattern('reference', (new GenericValidator)->getPattern());

/**
 * It's very likely that this route will change the state of the system,
 * so I will change the HTTP method to POST.
 */
Route::post('/notification/hook/{action}/{reference}', [NotificationController::class, 'hook'])->name('notification.hook');
