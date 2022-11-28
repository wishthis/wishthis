<?php

namespace Qferrer\Mjml\Http;

use Qferrer\Mjml\Exception\CurlException;

class Curl implements CurlInterface
{
    public function request(string $url, array $options = []): CurlResponseInterface
    {
        $request = @curl_init($url);

        if (false === $request) {
            throw new CurlException('Unable to initialize Curl.');
        }

        curl_setopt_array($request, $options);

        $data = curl_exec($request);

        if (false === $data) {
            throw new CurlException(sprintf('Curl Error: "%s"', curl_error($request)));
        }

        $response = new CurlResponse($data, curl_getinfo($request, CURLINFO_HTTP_CODE));

        curl_close($request);

        return $response;
    }
}
