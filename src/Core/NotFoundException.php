<?php

namespace App\Core;

use Throwable;

class NotFoundException extends InfrastructureException
{
    public function __construct($message = '', $code = 404, Throwable $previous = null)
    {
        parent::__construct(empty($message) ? 'Resource not found' : $message, $code, $previous);
    }
}
