<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Exceptions;

use Exception;
use Throwable;

class SendingInvoiceFailedException extends Exception
{
    public function __construct(
        string $message = "Failed to send invoice",
        int $code = 0,
        Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
