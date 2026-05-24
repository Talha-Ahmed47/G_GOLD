<?php

namespace App\Exceptions;

use Exception;

class InsufficientFundsException extends Exception
{
    public function __construct($message = "Insufficient funds in wallet.")
    {
        parent::__construct($message);
    }
}
