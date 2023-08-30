<?php

/**
 * Redirect to URL
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\User;

function redirect(string $target)
{
    $user = User::getCurrent();

    /**
     * Redirect user based on channel setting
     */
    $isDevEnvironment = defined('ENV_IS_DEV') && true === ENV_IS_DEV;
    $isHostInChannel  = false;

    /** Determine if host is a defined channel */
    if (defined('CHANNELS') && is_array(CHANNELS)) {
        foreach (CHANNELS as $channel) {
            if ($channel['host'] === $_SERVER['HTTP_HOST']) {
                $isHostInChannel = true;
                break;
            }
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
