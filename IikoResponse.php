<?php

namespace iiko;

use iiko\Exceptions;

class IikoResponse
{
    protected $iikoRequest;
    protected $body;
    protected $httpStatusCode;
    protected $headers;
    protected $hasError = false;

    protected $decodedBody;

    public function __construct(IikoRequest $request, $body, $httpStatusCode, $headers)
    {
        $this->iikoRequest = $request;
        $this->body = $body;
        $this->httpStatusCode = $httpStatusCode;
        $this->headers = $headers;

        $this->checkError();
        $this->decodeBody();
    }

    protected function decodeBody()
    {
        if (isset($this->headers['Content-Type'])) {
            if (!is_array($this->headers['Content-Type'])) {
                $this->headers['Content-Type'] = (array)$this->headers['Content-Type'];
            }

            foreach ($this->headers['Content-Type'] as $contentType) {
                $contentType = strtolower($contentType);

                if (false !== strpos($contentType, 'application/json')) {
                    $this->decodedBody = json_decode($this->body, true);
                    break;
                } else {
                    $this->decodedBody = [];
                }
            }
        }
    }

    protected function checkError()
    {
        if ($this->httpStatusCode >= 400) {
            $this->hasError = true;
        }
    }

    public function getDecodedBody()
    {
        return $this->decodedBody;
    }

    public function throwException()
    {
        if ($this->hasError()) {
            $message = '';
            switch ($this->httpStatusCode) {

                case '401':
                    throw new Exceptions\AuthenticationException($message, 401, null, $this->iikoRequest, $this);
                    break;
                case '400':
                    throw new Exceptions\BadRequestException($message, 400, null, $this->iikoRequest, $this);
                    break;
                case '404':
                    throw new Exceptions\NotFoundException($message, 404, null, $this->iikoRequest, $this);
                    break;
                case ($this->httpStatusCode >= 400 AND $this->httpStatusCode < 500):
                    throw new Exceptions\BadRequestException($message, $this->httpStatusCode, null, $this->iikoRequest, $this);
                    break;
                case ($this->httpStatusCode >= 500):
                    throw new Exceptions\ServerException($message, $this->httpStatusCode, null, $this->iikoRequest, $this);
                    break;
                default:
                    throw new Exceptions\IIkoException($message, $this->httpStatusCode, null, $this->iikoRequest, $this);
            }
        }
    }

    public function hasError()
    {
        return $this->hasError;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}