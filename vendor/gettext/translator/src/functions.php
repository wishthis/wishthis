<?php

use Gettext\TranslatorFunctions as Translator;

if (!function_exists('__')) {
    /**
     * Returns the translation of a string.
     */
    function __(string $original, ...$args): string
    {
        $text = Translator::getTranslator()->gettext($original);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('noop__')) {
    /**
     * Noop, marks the string for translation but returns it unchanged.
     */
    function noop__(string $original, ...$args): string
    {
        $text = Translator::getTranslator()->noop($original);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('n__')) {
    /**
     * Returns the singular/plural translation of a string.
     */
    function n__(string $original, string $plural, int $value, ...$args): string
    {
        $text = Translator::getTranslator()->ngettext($original, $plural, $value);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('p__')) {
    /**
     * Returns the translation of a string in a specific context.
     */
    function p__(string $context, string $original, ...$args): string
    {
        $text = Translator::getTranslator()->pgettext($context, $original);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('d__')) {
    /**
     * Returns the translation of a string in a specific domain.
     */
    function d__(string $domain, string $original, ...$args): string
    {
        $text = Translator::getTranslator()->dgettext($domain, $original);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('dp__')) {
    /**
     * Returns the translation of a string in a specific domain and context.
     */
    function dp__(string $domain, string $context, string $original, ...$args): string
    {
        $text = Translator::getTranslator()->dpgettext($domain, $context, $original);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('dn__')) {
    /**
     * Returns the singular/plural translation of a string in a specific domain.
     */
    function dn__(string $domain, string $original, string $plural, int $value, ...$args): string
    {
        $text = Translator::getTranslator()->dngettext($domain, $original, $plural, $value);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('np__')) {
    /**
     * Returns the singular/plural translation of a string in a specific context.
     */
    function np__(string $context, string $original, string $plural, int $value, ...$args): string
    {
        $text = Translator::getTranslator()->npgettext($context, $original, $plural, $value);
        return Translator::getFormatter()->format($text, $args);
    }
}

if (!function_exists('dnp__')) {
    /**
     * Returns the singular/plural translation of a string in a specific domain and context.
     */
    function dnp__(string $domain, string $context, string $original, string $plural, int $value, ...$args): string
    {
        $text = Translator::getTranslator()->dnpgettext($domain, $context, $original, $plural, $value);
        return Translator::getFormatter()->format($text, $args);
    }
}
