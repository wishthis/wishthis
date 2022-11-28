<?php

namespace Qferrer\Tests\Mjml\Http;

use PHPUnit\Framework\TestCase;
use Qferrer\Mjml\Http\CurlApi;
use Qferrer\Mjml\Exception\ApiException;
use Qferrer\Mjml\Http\CurlInterface;
use Qferrer\Mjml\Http\CurlResponseInterface;

class CurlApiTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|CurlInterface
     */
    private $curl;
    private $api;
    private $response;

    protected function setUp(): void
    {
        $this->response = $this->createMock(CurlResponseInterface::class);
        $this->curl = $this->createMock(CurlInterface::class);
        $this->curl->method('request')->willReturn($this->response);
        $this->api = new CurlApi('test', 'mysecret', $this->curl);
    }

    public function testGetHtml()
    {
        $html = '<p>Hello World</p>';

        $this->configureResponseMock(['html' => $html], 200);
        $this->curl->method('request')->with('https://api.mjml.io/v1/render', [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "UTF-8",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['mjml' => 'test']),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Basic " . base64_encode("test:mysecret")
            ]
        ]);

        $this->assertEquals($html, $this->api->getHtml('test'));
    }

    public function testGetHtmlWithInvalidStatusCode()
    {
        $this->configureResponseMock([], 400);
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unexpected HTTP code: 400. Api Error Message: "Unknown Error"');

        $this->api->getHtml('test');
    }

    public function testGetHtmlWithInvalidStatusCodeAndErrorMessage()
    {
        $this->configureResponseMock(['message' => 'Invalid JSON'], 400);
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unexpected HTTP code: 400. Api Error Message: "Invalid JSON"');

        $this->api->getHtml('test');
    }

    public function testGetHtmlWithInvalidContent()
    {
        $this->configureResponseMock('{invalid json}', 200);
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unable to decode the JSON response: "Syntax error".');

        $this->api->getHtml('test');
    }

    public function testGetMjmlVersion()
    {
        $this->configureResponseMock(['mjml_version' => '4.4.0'], 200);

        $this->assertEquals('4.4.0', $this->api->getMjmlVersion());
    }

    private function configureResponseMock($data, int $statusCode)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $this->response->method('getContent')->willReturn($data);
        $this->response->method('getStatusCode')->willReturn($statusCode);
    }
}
