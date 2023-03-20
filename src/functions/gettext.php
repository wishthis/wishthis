<?php

/**
 * Gettext
 */

namespace wishthis;

function __(string $text, string $context = null, User $user = null): string
{
    if (null === $user) {
        $user = isset($_SESSION['user']->id) ? $_SESSION['user'] : new User();
    }

    if (null !== $user->translations) {
        $translation = $user->translations->find($context, $text);

        if ($translation) {
            $translationText = $translation->getTranslation();

            if (!empty($translationText)) {
                return htmlentities($translationText);
            }
        }
    }

    return htmlentities($text);
}

function _n(string $singular, string $plural, int $amount): string
{
    return 1 === $amount ? __($singular) : __($plural);
}

function _x(string $text, string $context = null): string
{
    return __($text, $context);
}
