<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Exceptions;

class InvoiceCreationFailedException extends \Exception
{
    protected $message = 'Invoice creation failed';

    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null)
    {
        if ($message) {
            $this->message = $message;
        }

        parent::__construct($this->message, $code, $previous);
    }
}
