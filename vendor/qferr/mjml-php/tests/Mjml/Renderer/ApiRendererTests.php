<?php

namespace Qferrer\Tests\Mjml\Renderer;

use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;
use Qferrer\Mjml\Renderer\ApiRenderer;
use Qferrer\Tests\ResourcesTrait;

class ApiRendererTests extends TestCase
{
    use ResourcesTrait;

    public function testRender()
    {
        $renderer = new ApiRenderer($_ENV['MJML_API_ID'], $_ENV['MJML_API_SECRET_KEY']);

        $html = $renderer->render($this->loadResource('hello_world.mjml'));

        $this->assertEquals($this->loadResource('hello_world.html'), $html);
    }

    public function testRenderWithInvalidCredentialsThrowException()
    {
        $this->expectException(ClientException::class);

        $renderer = new ApiRenderer('test', 'test');
        $renderer->render($this->loadResource('hello_world.html'));
    }
}
