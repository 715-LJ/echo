<?php

namespace App\Exceptions;

use App\Constants\ErrorCode;
use Exception;
use Illuminate\Http\Response as HttpResponse;
use Throwable;

class ApiException extends Exception
{
    public function __construct($code, $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
