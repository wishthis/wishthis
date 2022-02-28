<?php

namespace Qferrer\Mjml\Renderer;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
     * BinaryRenderer constructor.
     *
     * @param string $bin
     */
    public function __construct(string $bin)
    {
        $this->bin = $bin;
    }

    /**
     * @inheritDoc
     */
    public function render(string $content): string
    {
        $arguments = [
            $this->bin,
            '-i',
            '-s',
            '--config.validationLevel',
            '--config.minify'
        ];

        $process = new Process($arguments);
        $process->setInput($content);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new \RuntimeException('Unable to compile MJML. Stack error: '.$e->getMessage());
        }

        return $process->getOutput();
    }
}
