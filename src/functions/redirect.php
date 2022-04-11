<?php

/**
 * Redirect to URL
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function redirect(string $target)
{
    global $user;

    $isDevEnvironment = defined('ENV_IS_DEV') && true === ENV_IS_DEV;

    /**
     * Redirect user based on channel setting
     */
    $isHostInChannel = false;

    /** Determine if host is a defined channel */
    foreach (CHANNELS as $channel) {
        if ($channel['host'] === $_SERVER['HTTP_HOST']) {
            $isHostInChannel = true;
            break;
        }
    }

    /** Determine channel to redirect to */
    if (
           defined('CHANNELS')
        && is_array(CHANNELS)
        && isset($user->channel)
        && !$isDevEnvironment
    ) {
        $host = null;

        foreach (CHANNELS as $channel) {
            if (
                   $channel['branch'] === $user->channel
                && $channel['host']   !== $_SERVER['HTTP_HOST']
                && $isHostInChannel
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
