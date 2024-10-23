<?php

namespace app\exceptions;

use Exception;
use Throwable;

class ForbiddenAccessException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
