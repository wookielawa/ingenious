<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Invoices\Application\UseCases\SendInvoiceUseCase;
use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Exceptions\InvoiceUpdateFailedException;
use Modules\Invoices\Domain\Exceptions\SendingInvoiceFailedException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

final class SendInvoiceController extends Controller
{
    public function __construct(
        private readonly SendInvoiceUseCase $useCase,
    ) {}

    public function __invoke(string $invoiceId): JsonResponse
    {
        try {
            $this->useCase->execute($invoiceId);

            return response()->success([], HttpResponse::HTTP_NO_CONTENT);
        } catch (InvoiceNotFoundException $exception) {
            return response()->error($exception->getMessage(),
                HttpResponse::HTTP_NOT_FOUND);
        } catch (SendingInvoiceFailedException|InvoiceUpdateFailedException) {
            return response()->error(
                'Failed to send invoice. Please try again later.',
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            );
        } catch (Throwable $exception) {
            Log::error('Error sending invoice', [
                'invoice_id' => $invoiceId,
                'exception'  => $exception,
            ]);

            return response()->error(
                'An unexpected error occurred',
                HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
