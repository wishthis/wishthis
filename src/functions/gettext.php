<?php

/**
 * gettext.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use Gettext\Loader\PoLoader;

function __(string $text)
{
    global $page;

    /**
     * Use file
     */
    $translationFilepath = ROOT . '/translations/' . $page->language . '.po';

    if (file_exists($translationFilepath)) {
        $loader       = new PoLoader();
        $translations = $loader->loadFile($translationFilepath);
        $translation  = $translations->find(null, $text);

        if ($translation) {
            return $translation->getTranslation();
        }
    }

    return $text;
}
