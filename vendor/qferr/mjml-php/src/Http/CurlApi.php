<?php

declare(strict_types=1);

namespace Qferrer\Mjml\Http;

use Qferrer\Mjml\ApiInterface;
use Qferrer\Mjml\Exception\ApiException;

/**
 * @see https://mjml.io/api/documentation/
 */
final class CurlApi implements ApiInterface
{
    protected $apiEndpoint = "https://api.mjml.io/v1";
    protected $appId;
    protected $secretKey;
    protected $credentials;
    protected $curl;

    public function __construct(string $appId, string $secretKey, ?CurlInterface $curl = null)
    {
        if (!\extension_loaded('curl')) {
            throw new \LogicException(sprintf(
                'You cannot use the "%s" as the "curl" extension is not installed.',
                CurlApi::class
            ));
        }

        $this->appId = $appId;
        $this->secretKey = $secretKey;
        $this->credentials = base64_encode("$appId:$secretKey");
        $this->curl = $curl ?? new Curl();
    }

    public function getHtml(string $mjml): string
    {
        $data = $this->getResult($mjml);

        return $data['html'] ?? '';
    }

    public function getMjmlVersion(): string
    {
        $data = $this->getResult('<mjml></mjml>');

        return $data['mjml_version'] ?? 'unknown';
    }

    private function getResult(string $mjml): array
    {
        $response = $this->curl->request($this->apiEndpoint . '/render', [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "UTF-8",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['mjml' => $mjml]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Basic {$this->credentials}"
            ]
        ]);

        $data = @json_decode($response->getContent(), true);

        if (false === $data || null === $data) {
            throw new ApiException(sprintf(
                'Unable to decode the JSON response: "%s".',
                json_last_error_msg()
            ));
        }

        $httpCode = $response->getStatusCode();

        if ($httpCode !== 200) {
            throw new ApiException(sprintf(
                'Unexpected HTTP code: %s. Api Error Message: "%s".',
                $httpCode,
                $data['message'] ?? 'Unknown Error'
            ));
        }

        return $data;
    }
}
