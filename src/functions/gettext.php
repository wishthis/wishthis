<?php

/**
 * gettext.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

function __(string $text)
{
    global $translations;

    $translation = null;

    if ($translations) {
        $translation = $translations->find(null, $text);

        if ($translation) {
            return addslashes($translation->getTranslation());
        }
    }

    return addslashes($text);
}
