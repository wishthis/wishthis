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

    public string $version;

    public function __construct(private Database $database)
    {
        $this->version = $this->getOption('version')
                       ? $this->getOption('version')
                       : '0.1.0';
    }

    public function getOption(string $key): string
    {
        $value = '';

        try {
            $option = $this->database->query(
                'SELECT * FROM `options`
                 WHERE `key` = "' . $key . '";'
            )->fetch();

            $value = $option['value'] ?? '';
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $value;
    }

    public function setOption(string $key, string $value): void
    {
        try {
            $option = $this->database->query('UPDATE `options`
                                                 SET `value`
                                               WHERE `key` = ' . $key . '
            ;');

            $value = $option['value'] ?? '';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
