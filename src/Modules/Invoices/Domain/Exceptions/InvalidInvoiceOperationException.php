<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Exceptions;

use Exception;
use Throwable;

class InvalidInvoiceOperationException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
