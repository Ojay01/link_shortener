<?php

namespace App\Exceptions;

use Exception;

class InvalidUrlException extends Exception
{
       public function __construct(string $message = 'Invalid URL provided', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
