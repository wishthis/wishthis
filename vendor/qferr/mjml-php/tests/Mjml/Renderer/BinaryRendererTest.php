<?php

namespace Qferrer\Tests\Mjml\Renderer;

use PHPUnit\Framework\TestCase;
use Qferrer\Mjml\Renderer\BinaryRenderer;
use Qferrer\Tests\ResourcesTrait;

class BinaryRendererTest extends TestCase
{
    use ResourcesTrait;

    public function testRender()
    {
        $renderer = new BinaryRenderer(__DIR__ . '/../../../node_modules/.bin/mjml');

        $html = $renderer->render($this->loadResource('hello_world.mjml'));

        $this->assertEquals($this->loadResource('hello_world.min.html'), $html);
    }

    public function testRenderWithInvalidBinaryThrowException()
    {
        $this->expectException(\RuntimeException::class);

        $renderer = new BinaryRenderer('unknown');
        $renderer->render($this->loadResource('hello_world.mjml'));
    }
}
