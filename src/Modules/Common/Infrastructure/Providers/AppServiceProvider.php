<?php

namespace Modules\Common\Infrastructure\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot(): void
    {
        Response::macro('success', function ($data, $code = HttpResponse::HTTP_OK) {
            if ($data instanceof JsonSerializable) {
                $data = $data->jsonSerialize();
            }

            return response()->json($data, $code);
        });

        Response::macro('error', function ($message, $code = HttpResponse::HTTP_BAD_REQUEST) {
            return response()->json(['error' => $message], $code);
        });
    }
}
