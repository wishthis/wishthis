<?php

namespace Qferrer\Tests\Mjml\Renderer;

use PHPUnit\Framework\TestCase;
use Qferrer\Mjml\ApiInterface;
use Qferrer\Mjml\Http\CurlApi;
use Qferrer\Mjml\Renderer\ApiRenderer;

class ApiRendererTests extends TestCase
{
    private $httpClient;
    private $apiRenderer;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ApiInterface::class);
        $this->apiRenderer = new ApiRenderer($this->httpClient);
    }

    public function testRender()
    {
        $expectedHtml = '<p>Test</p>';
        $this->httpClient->expects($this->once())->method('getHtml')->willReturn($expectedHtml);

        $html = $this->apiRenderer->render('<mjml>Test</mjml>');

        $this->assertEquals($expectedHtml, $html);
    }
}
