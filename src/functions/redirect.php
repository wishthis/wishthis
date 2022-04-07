<?php

/**
 * Redirect to URL
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function redirect(string $target)
{
    global $user;

    if (
           defined('CHANNELS')
        && is_array(CHANNELS)
        && isset($user->channel)
        && '127.0.0.1' !== $_SERVER['REMOTE_ADDR']
    ) {
        $host = null;

        foreach (CHANNELS as $channel) {
            if (
                   $channel['branch'] === $user->channel
                && $channel['host']   !== $_SERVER['HTTP_HOST']
            ) {
                $host = $channel['host'];
                break;
            }
        }

        if (null !== $host) {
            $target = 'https://' . $host . $target;

            header('Location: ' . $target);
            die();
        }
    }

    if ($target !== $_SERVER['REQUEST_URI']) {
        header('Location: ' . $target);
        die();
    }
}
