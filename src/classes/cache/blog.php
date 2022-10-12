<?php

/**
 * WordPress blog cache.
 */

namespace wishthis\Cache;

class Blog extends Cache
{
    /**
     * Private
     */
    protected function getFilepath(): string
    {
        return parent::getFilepath() . '.json';
    }

    /**
     * Public
     */
    public function __construct($url)
    {
        parent::__construct($url, \wishthis\Duration::DAY);

        $this->directory .= '/blog';
    }

    public function get(): \stdClass|array
    {
        $filepath = $this->getFilepath();

        $response = $this->exists() ? json_decode(file_get_contents($filepath)) : array();

        if (true === $this->generateCache() || empty($response)) {
            $postsRemote = file_get_contents($this->url);

            if (false !== $postsRemote) {
                $response = json_decode($postsRemote);
            }

            $this->write($response);
        }

        return $response;
    }
}
