<?php

/**
 * include-directory.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace Grandel;

class IncludeDirectory
{
    /**
     * Include Directory
     *
     * @param string $directoryToInclude The directory to include.
     *
     * @return void
     */
    public function __construct(string $directoryToInclude)
    {
        self::includeDirectory($directoryToInclude);
    }

    private static function includeDirectory(string $directoryToInclude)
    {
        foreach (scandir($directoryToInclude) as $filename) {
            if ($filename === '.' || $filename === '..') {
                continue;
            }

            $filepath = str_replace('\\', '/', $directoryToInclude . '/' . $filename);

            if (is_file($filepath)) {
                require $filepath;
            }

            if (is_dir($filepath)) {
                self::includeDirectory($filepath);
            }
        }
    }
}
