<?php

/**
 * Delete a directory with all of its contents.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function delete_directory(string $directoryToDelete)
{
    foreach (scandir($directoryToDelete) as $filename) {
        if (in_array($filename, array('.', '..'))) {
            continue;
        }

        $filepath = $directoryToDelete . '/' . $filename;

        if (is_file($filepath) && !is_dir($filepath)) {
            unlink($filepath);
        }

        if (is_dir($filepath)) {
            delete_directory($filepath);
        }
    }

    unlink($directoryToDelete);
}
