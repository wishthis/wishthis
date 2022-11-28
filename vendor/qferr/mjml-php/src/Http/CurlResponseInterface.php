<?php

namespace Qferrer\Mjml\Http;

interface CurlResponseInterface
{
    /**
     * @return mixed
     */
    public function getContent();

    public function getStatusCode(): int;
}
