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
    private int $maxAge;

    /**
     * Protected
     */
    protected string $url;
    protected string $directory = ROOT . '/src/cache';

    protected function getAge(): int
    {
        return time() - $this->getLastModified();
    }

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
        $filepath       = $this->getFilepath();
        $directoryName  = dirname($filepath);
        $directoryCache = dirname($directoryName);

        if (false === file_exists($directoryCache)) {
            mkdir($directoryCache);
        }

        if (false === file_exists($directoryName)) {
            mkdir($directoryName);
        }

        file_put_contents($filepath, json_encode($value));
    }

    /**
     * Public
     */
    public function __construct(string $url, int $maxAge = \wishthis\Duration::YEAR)
    {
        $this->url    = trim($url);
        $this->maxAge = $maxAge;
    }

    public function exists(): bool
    {
        return file_exists($this->getFilepath());
    }

    public function getLastModified(): int
    {
        return filemtime($this->getFilepath());
    }

    public function generateCache(): bool
    {
        return !$this->exists()
            || ($this->exists() && $this->getAge() > $this->maxAge);
    }
}
