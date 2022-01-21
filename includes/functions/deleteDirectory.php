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
            echo 'unlink: ' . $filepath . '<br>';
            // unlink($filepath);
        }

        if (is_dir($filepath)) {
            echo 'delete_directory: ' . $filepath . '<br>';
            // delete_directory($filepath);
        }
    }

    echo 'rmdir (final): ' . $filepath . '<br>';
    // rmdir($directoryToDelete);
}
