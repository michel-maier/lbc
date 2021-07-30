<?php

namespace App\Core;

use Throwable;

class UniqueConstraintViolationException extends InfrastructureException
{
    public function __construct($message = '', $code = 400, Throwable $previous = null)
    {
        parent::__construct(empty($message) ? 'A resource with the same identity exists' : $message, $code, $previous);
    }
}
