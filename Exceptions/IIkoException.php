<?php

namespace iiko\Exceptions;

use iiko\IikoRequest;
use iiko\IikoResponse;
use Throwable;

class IIkoException extends \Exception
{
    protected $request;
    protected $response;

    public function __construct($message = "", $code = 0, Throwable $previous = null, IikoRequest $request, IikoResponse $response = null)
    {
        $this->request = $request;
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }
}