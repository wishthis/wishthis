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
    private array $placeholders = array();

    /**
     * Public
     */
    public function __construct(string $url, array $placeholders = array(), int $maxAge = \wishthis\Duration::YEAR)
    {
        global $database;

        parent::__construct($url, $maxAge);

        $this->directory .= '/query';
        $this->database   = $database;
    }

    public function get(): array
    {
        $filepath = $this->getFilepath();

        $response = $this->exists() ? json_decode(file_get_contents($filepath), true) : array();

        if (true === $this->generateCache()) {
            $pdoStatement = $this->database
            ->query($this->url, $this->placeholders);

            if (false !== $pdoStatement) {
                if (1 === $pdoStatement->rowCount()) {
                    $response = $pdoStatement->fetch();
                } else {
                    $response = $pdoStatement->fetchAll();
                }
            }

            $this->write($response);
        }

        return $response;
    }
}
