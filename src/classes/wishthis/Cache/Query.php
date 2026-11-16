<?php

/**
 * Databse query cache
 */

namespace wishthis\Cache;

class Query extends Cache
{
    /**
     * Private
     */
    private \wishthis\Database $database;
    private array $placeholders = [];

    /**
     * Public
     */
    public function __construct(string $url, array $placeholders = [], int $maxAge = \wishthis\Duration::YEAR)
    {
        global $database;

        parent::__construct($url, $maxAge);

        $this->directory .= '/query';
        $this->database   = $database;
    }

    public function get(): array
    {
        $filepath = $this->getFilepath();

        $response = $this->exists() ? \json_decode(\file_get_contents($filepath), true) : [];

        if (true === $this->generateCache()) {
            $pdoStatement = $this->database
            ->query($this->url, $this->placeholders);

            if (false !== $pdoStatement) {
                $response = $pdoStatement->fetch();
            }

            $this->write($response);
        }

        return $response;
    }
}
