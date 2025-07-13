<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\UseCases;

use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;

readonly class ShowInvoiceUseCase {

    public function __construct(
        private InvoiceRepository $repository
    ) {}

    /**
     * @throws InvoiceNotFoundException
     */
    public function execute(string $invoiceUuid): Invoice
    {
        return $this->repository->findById($invoiceUuid);
    }

}
