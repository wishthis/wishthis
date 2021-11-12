<?php

/**
 * auto-include.php
 *
 * Includes all *.php files in a given directory (including sub directories).
 *
 * @param string $directoryToInclude The absolute directory to include.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function autoInclude(string $directoryToInclude)
{
    foreach (scandir($directoryToInclude) as $filename) {
        $filepath     = str_replace('\\', '/', $directoryToInclude . '/' . $filename);
        $filepathThis = str_replace('\\', '/', __FILE__);

        if (is_file($filepath) && $filepathThis !== $filepath) {
            require $filepath;
        }
    }
}
