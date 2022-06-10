<?php

/**
 * Databse query cache
 */

namespace wishthis\Cache;

class Query extends Cache
{
    private Database $databse;

    public function __construct($url)
    {
        global $database;

        parent::__construct($url);

        $this->directory .= '/query';
        $this->database = $database;
    }

    public function get(): array
    {
        $filepath = $this->getFilepath();

        $response = $this->exists() ? json_decode(file_get_contents($filepath), true) : array();

        if (true === $this->generateCache()) {
            $pdoStatement = $this->database->query($this->url);

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
