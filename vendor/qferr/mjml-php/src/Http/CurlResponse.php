<?php

namespace Qferrer\Mjml\Http;

class CurlResponse implements CurlResponseInterface
{
    private $data;
    private $statusCode;

    public function __construct($data, int $statusCode)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function getContent()
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
