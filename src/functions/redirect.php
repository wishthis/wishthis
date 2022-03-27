<?php

/**
 * Redirect to URL
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function redirect(string $target)
{
    if ($target !== $_SERVER['REQUEST_URI']) {
        header('Location: ' . $target);
        die();
    }
}
