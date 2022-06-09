<?php

/**
 * WordPress blog cache.
 */

namespace wishthis;

class BlogCache extends Cache
{
    public function __construct(private string $url)
    {
        $this->$directory = parent::$directory . '/blog';
    }
}
