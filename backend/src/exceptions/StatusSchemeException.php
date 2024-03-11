<?php

namespace src\exceptions;

use \Exception;
use \Throwable;

class StatusSchemeException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}