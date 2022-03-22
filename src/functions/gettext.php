<?php

/**
 * gettext.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use Gettext\Loader\PoLoader;
use Gettext\Generator\MoGenerator;

function __(string $text)
{
    $userLocale          = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $translationFilepath = ROOT . '/translations/' . $userLocale . '.po';

    if (file_exists($translationFilepath)) {
        $loader       = new PoLoader();
        $translations = $loader->loadFile(ROOT . '/translations/de_DE.po');
        $translation  = $translations->find(null, $text);

        if ($translation) {
            return $translation->getTranslation();
        }
    }

    return $text;
}
