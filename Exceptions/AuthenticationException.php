<?php

namespace iiko\Exceptions;

use iiko\IikoRequest;
use iiko\IikoResponse;
use Throwable;

class AuthenticationException extends IIkoException
{
    public function __construct($message = "", $code = 0, $previous = null, IikoRequest $request, IikoResponse $response = null)
    {
        parent::__construct($message, $code, $previous, $request, $response);
    }
}