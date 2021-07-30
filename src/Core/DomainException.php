<?php

namespace App\Core;

use RuntimeException;
use Throwable;

class DomainException extends RuntimeException
{
    public function __construct($message = '', $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
