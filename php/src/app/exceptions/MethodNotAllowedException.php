<?php

namespace app\exceptions;

use Exception;
use Throwable;

class MethodNotAllowedException extends Exception
{
    // Previous is previous exception that caused this exception to be thrown, so it can be traced and only be intercepted in view layer
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
