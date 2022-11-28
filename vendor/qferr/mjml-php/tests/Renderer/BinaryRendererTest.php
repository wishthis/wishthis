<?php

namespace Qferrer\Tests\Mjml\Renderer;

use PHPUnit\Framework\TestCase;
use Qferrer\Mjml\Exception\ProcessException;
use Qferrer\Mjml\Renderer\BinaryRenderer;
use Qferrer\Tests\Mjml\ResourcesTrait;

class BinaryRendererTest extends TestCase
{
    use ResourcesTrait;

    public function testRender()
    {
        $renderer = new BinaryRenderer(__DIR__ . '/../../node_modules/.bin/mjml');

        $html = $renderer->render($this->loadResource('hello_world.mjml'));

        $this->assertEquals($this->loadResource('hello_world.min.html.text'), $html);
    }

    public function testRenderWithInvalidBinaryThrowException()
    {
        $this->expectException(ProcessException::class);
        $this->expectExceptionMessage('unknown');

        $renderer = new BinaryRenderer('unknown');
        $renderer->render($this->loadResource('hello_world.mjml'));
    }

    public function testRenderWithMalformedMjml()
    {
        $this->expectException(ProcessException::class);
        $this->expectExceptionMessage('Malformed MJML. Check that your structure is correct and enclosed in <mjml> tags.');

        $renderer = new BinaryRenderer(__DIR__ . '/../../node_modules/.bin/mjml');
        $renderer->render('<mljm></mljm>');
    }
}
