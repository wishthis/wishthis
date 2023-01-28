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
                'SELECT *
                   FROM `options`
                  WHERE `key` = :option_key',
                array(
                    'option_key' => Sanitiser::getOption($key),
                )
            )
            ->fetch();

            $value = $option['value'] ?? '';
        } catch (\Throwable $th) {
            /** Option does not exist */
        }

        return $value;
    }

    public function setOption(string $key, string $value): void
    {
        $optionExists = 0 !== $this->database
        ->query(
            'SELECT *
               FROM `options`
              WHERE `key` = :option_key;',
            array(
                'option_key' => $key,
            )
        )
        ->rowCount();

        if ($optionExists) {
            $this->database->query(
                'UPDATE `options`
                    SET `value` = :option_value
                  WHERE `key`   = :option_key;',
                array(
                    'option_value' => $value,
                    'option_key'   => $key,
                )
            );
        } else {
            $this->database->query(
                'INSERT INTO `options`
                    (`key`, `value`)
                VALUES
                    (:option_key, :option_value);',
                array(
                    'option_key'   => $key,
                    'option_value' => $value,
                )
            );
        }
    }
}
