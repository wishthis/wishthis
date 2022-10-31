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
                 WHERE `key` = "' . Sanitiser::getOption($key) . '";'
            )->fetch();

            $value = $option['value'] ?? '';
        } catch (\Throwable $th) {
            /** Option does not exist */
        }

        return $value;
    }

    public function setOption(string $key, string $value): void
    {
        $key   = Sanitiser::getOption($key);
        $value = Sanitiser::getText($value);

        $optionExists = 0 !== $this->database
        ->query('SELECT *
                   FROM `options`
                  WHERE `key` = "' . $key . '";')
        ->rowCount();

        if ($optionExists) {
            $this->database->query('UPDATE `options`
                                       SET `value` = "' . $value . '"
                                     WHERE `key`   = "' . $key . '"
                           ;');
        } else {
            $this->database->query('INSERT INTO `options`
                                   (`key`, `value`) VALUES
                                   ("' . $key . '", "' . $value . '")
                           ;');
        }
    }
}
