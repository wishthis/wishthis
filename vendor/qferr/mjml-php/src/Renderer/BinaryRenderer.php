<?php

namespace Qferrer\Mjml\Renderer;

use Qferrer\Mjml\Process\Process;
use Qferrer\Mjml\RendererInterface;

/**
 * Class BinaryRenderer
 */
class BinaryRenderer implements RendererInterface
{
    /**
     * The MJML CLI path.
     *
     * @var string
     */
    private $bin;

    /**
     * @var string
     */
    private $command;

    /**
     * BinaryRenderer constructor.
     *
     * @param string $bin
     */
    public function __construct(string $bin)
    {
        $this->bin = $bin;
        $this->command = "{$this->bin} -i -s --config.validationLevel --config.minify";
    }

    /**
     * @inheritDoc
     */
    public function render(string $content): string
    {
        $process = new Process($this->command, $content);
        $process->run();

        return $process->getOutput();
    }
}
