<?php

namespace Qferrer\Tests;

trait ResourcesTrait
{
    private function loadResource($filename)
    {
        return file_get_contents(__DIR__ . '/Resources/' . $filename);
    }
}
