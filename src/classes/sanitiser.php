<?php

namespace wishthis;

class Sanitiser
{
    public static function render(string $text): string
    {
        return $text;
    }

    public static function getNumber(mixed $valueToSanitise): float|int
    {
        $number = preg_replace('/[^0-9\.]+/', '', $valueToSanitise);

        return $number;
    }

    public static function getPage(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z\-_]+/', '', $valueToSanitise);
    }

    public static function getTable(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z_]+/', '', $valueToSanitise);
    }

    public static function getTitle(mixed $valueToSanitise): string
    {
        $valueToSanitise = trim($valueToSanitise);

        return htmlentities($valueToSanitise, ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5);
    }

    public static function getText(mixed $valueToSanitise): string
    {
        $valueToSanitise = trim($valueToSanitise);

        return htmlentities($valueToSanitise, ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5);
    }

    public static function getURL(mixed $valueToSanitise): string
    {
        return preg_replace('/[\s]+/', '', $valueToSanitise);
    }

    public static function getStatus(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z\-_]+/', '', $valueToSanitise);
    }

    public static function getOption(mixed $valueToSanitise): string
    {
        return preg_replace('/[^a-zA-Z\_]+/', '', $valueToSanitise);
    }

    public static function getEmail(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z\-_0-9\.+@]+/', '', $valueToSanitise);
    }

    public static function getSHA1(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-f0-9]+/', '', $valueToSanitise);
    }
}
