<?php

namespace Qferrer\Mjml\Renderer;

use GuzzleHttp\Client;

/**
 * Class ApiRenderer
 */
class ApiRenderer implements RendererInterface
{
    /**
     * The client
     *
     * @var Client
     */
    private $client;

    /**
     * The application ID
     *
     * @var string
     */
    private $appId;

    /**
     * The secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * The MJML base uri.
     *
     * @var string
     */
    const BASE_URI = "https://api.mjml.io/v1/";

    /**
     * ApiRenderer constructor.
     *
     * @param string $appId
     * @param string $secretKey
     */
    public function __construct(string $appId, string $secretKey)
    {
        $this->appId = $appId;
        $this->secretKey = $secretKey;
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    /**
     * @inheritDoc
     */
    public function render(string $content): string
    {
        $response = $this->client->post('render', [
            'auth' => [$this->appId, $this->secretKey],
            'body' => json_encode([
                'mjml' => $content
            ])
        ]);

        if (false === $data =  json_decode($response->getBody()->getContents(), true)) {
            throw new \RuntimeException('Unable to decode response');
        }

        return $data['html'];
    }
}