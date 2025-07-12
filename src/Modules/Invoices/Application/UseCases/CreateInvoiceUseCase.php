<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\UseCases;

use Modules\Invoices\Application\Dtos\CreateInvoiceDTO;
use Modules\Invoices\Domain\Factories\InvoiceFactory;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Repositories\InvoiceRepository;

readonly class CreateInvoiceUseCase
{
    public function __construct(
        private InvoiceRepository $repository,
        private InvoiceFactory $factory,
    ) {}

    public function execute(CreateInvoiceDTO $createInvoiceDTO): Invoice
    {
        return $this->repository->save(
            $this->factory->create($createInvoiceDTO->toArray()),
        );
    }
}
