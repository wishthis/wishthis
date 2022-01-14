<?php

/**
 * options.php
 *
 * Store and retrieve application options.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Options
{
    public bool $updateAvailable = false;

    public function __construct(private Database $database)
    {
    }

    public function getOption(string $key): string
    {
        $option = $this->database->query(
            'SELECT * FROM `options`
             WHERE `key` = "' . $key . '";'
        )->fetch();

        return $option['value'] ?? '';
    }
}
