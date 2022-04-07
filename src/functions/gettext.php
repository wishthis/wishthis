<?php

/**
 * gettext.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function __(string $text): string
{
    global $translations;

    $translation = null;

    if ($translations) {
        $translation = $translations->find(null, $text);

        if ($translation) {
            return htmlentities($translation->getTranslation());
        }
    }

    return htmlentities($text);
}

function _n(string $singular, string $plural, int $amount): string
{
    return 1 === $amount ? __($singular) : __($plural);
}
