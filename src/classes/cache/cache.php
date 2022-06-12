<?php

/**
 * Generic cache class
 */

namespace wishthis\Cache;

class Cache
{
    /**
     * Private
     */
    private function getAge(): int
    {
        return time() - filemtime($this->getFilepath());
    }

    /**
     * Protected
     */
    protected string $directory = ROOT . '/src/cache';
    protected int    $maxAge    = 2592000; // 30 days

    protected function getIdentifier(): string
    {
        return md5($this->url);
    }

    protected function getFilepath(): string
    {
        return $this->directory . '/' . $this->getIdentifier();
    }

    protected function write(mixed $value): void
    {
        $filepath  = $this->getFilepath();
        $directory = dirname($filepath);

        if (false === file_exists($directory)) {
            mkdir($directory);
        }

        file_put_contents($filepath, json_encode($value));
    }

    /**
     * Public
     */
    public function __construct(protected string $url)
    {
    }

    public function exists(): bool
    {
        return file_exists($this->getFilepath());
    }

    public function generateCache(): bool
    {
        return !$this->exists()
            || ($this->exists() && $this->getAge() > $this->maxAge);
    }
}
