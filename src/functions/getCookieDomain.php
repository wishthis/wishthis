<?php

/**
 * Get cookie domain
 */
function getCookieDomain(): string
{
    $cookieDomain = $_SERVER['HTTP_HOST'];

    if (defined('CHANNELS') && is_iterable(CHANNELS) && defined('ENV_IS_DEV') && ! ENV_IS_DEV && '127.0.0.1' !== $_SERVER['REMOTE_ADDR']) {
        foreach (CHANNELS as $channel) {
            if ('stable' === $channel['branch']) {
                $cookieDomain = $channel['host'];

                break;
            }
        }
    }

    return '.' . $cookieDomain;
}
