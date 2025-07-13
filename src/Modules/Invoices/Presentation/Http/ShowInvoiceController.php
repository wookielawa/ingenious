<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Routing\Controller;
use Modules\Invoices\Application\UseCases\ShowInvoiceUseCase;
use Illuminate\Http\JsonResponse;
use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class ShowInvoiceController extends Controller
{
    public function __construct(
        private readonly ShowInvoiceUseCase $showInvoiceUseCase,
    ) {}

    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $invoice = $this->showInvoiceUseCase->execute($uuid);

            return response()->success($invoice, HttpResponse::HTTP_OK);
        } catch (InvoiceNotFoundException $exception) {
            return response()->error($exception->getMessage(), HttpResponse::HTTP_NOT_FOUND);
        } catch (Throwable) {
            return response()->error(
                'An unexpected error occurred while retrieving the invoice.',
                HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
