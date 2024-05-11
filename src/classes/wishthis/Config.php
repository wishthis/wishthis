<?php

namespace wishthis;

class Config
{
    public function __construct(private string $filePath)
    {
    }

    public function exists(): bool
    {
        return \file_exists($this->filePath);
    }

    public function load(): bool
    {
        $exists = $this->exists();

        if ($exists) {
            require $this->filePath;
        }

        return $exists;
    }
}
