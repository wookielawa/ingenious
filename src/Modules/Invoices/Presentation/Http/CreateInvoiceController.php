<?php

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Invoices\Application\Dtos\CreateInvoiceDTO;
use Modules\Invoices\Application\UseCases\CreateInvoiceUseCase;
use Modules\Invoices\Domain\Exceptions\InvoiceCreationFailedException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

final class CreateInvoiceController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        private readonly CreateInvoiceUseCase $createInvoiceUseCase,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $model = $this->createInvoiceUseCase->execute(
                CreateInvoiceDTO::fromArray($request->all())
            );

            return response()->success($model, HttpResponse::HTTP_CREATED);
        } catch (InvoiceCreationFailedException $exception) {
            return response()->error($exception->getMessage(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable) {
            return response()->error(
                'An unexpected error occurred while creating the invoice.',
                HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
