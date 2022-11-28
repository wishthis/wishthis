<?php

namespace Qferrer\Mjml\Process;

use Qferrer\Mjml\Exception\ProcessException;

class Process
{
    private $command;
    private $input;
    private $output;
    private $errorMessage = '';

    public const STDIN = 0;
    public const STDOUT = 1;
    public const STDERR = 2;

    private static $descriptors = [
        self::STDIN => ['pipe', 'r'],
        self::STDOUT => ['pipe', 'w'],
        self::STDERR => ['pipe', 'w']
    ];

    public function __construct(string $command, string $input)
    {
        $this->command = $command;
        $this->input = $input;
    }

    public function run(): void
    {
        $this->initialize();

        $pipes = [];
        $process = $this->createProcess($pipes);

        $this->setInput($pipes);
        $this->setOutput($pipes);
        $this->setErrorMessage($pipes);

        if (0 !== proc_close($process)) {
            throw new ProcessException($this->errorMessage);
        }
    }

    public function getOutput()
    {
        return $this->output;
    }

    private function initialize(): void
    {
        $this->output = null;
        $this->errorMessage = '';
    }

    /**
     * @return resource
     */
    private function createProcess(array &$pipes)
    {
        $process = proc_open($this->command, self::$descriptors, $pipes);

        if (!is_resource($process)) {
            throw new ProcessException('Unable to create a process.');
        }

        return $process;
    }

    private function setInput(array $pipes): void
    {
        $stdin = $pipes[self::STDIN];
        fwrite($stdin, $this->input);
        fclose($stdin);
    }

    private function setOutput(array $pipes): void
    {
        $stdout = $pipes[self::STDOUT];
        $this->output = stream_get_contents($stdout);
        fclose($stdout);
    }

    private function setErrorMessage(array $pipes): void
    {
        $stderr = $pipes[self::STDERR];
        $this->errorMessage = stream_get_contents($stderr);
        fclose($stderr);
    }
}
