<?php

namespace Qferrer\Mjml\Http;

interface CurlInterface
{
    public function request(string $url, array $options = []): CurlResponseInterface;
}
