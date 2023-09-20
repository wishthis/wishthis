<?php

namespace wishthis;

class Sanitiser
{
    /**
     * @deprecated 1.1.0
     */
    public static function render(string $text): string
    {
        return $text;
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getNumber(mixed $valueToSanitise): float|int
    {
        $number = preg_replace('/[^0-9\.]+/', '', $valueToSanitise);

        return $number;
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getPage(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z\-_]+/', '', $valueToSanitise);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getTable(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z_]+/', '', $valueToSanitise);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getTitle(mixed $valueToSanitise): string
    {
        $valueToSanitise = trim($valueToSanitise);

        return htmlentities($valueToSanitise, ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getText(mixed $valueToSanitise): string
    {
        $valueToSanitise = trim($valueToSanitise);

        return htmlentities($valueToSanitise, ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getURL(mixed $valueToSanitise): string
    {
        return preg_replace('/[\s]+/', '', $valueToSanitise);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getStatus(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z\-_]+/', '', $valueToSanitise);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getOption(mixed $valueToSanitise): string
    {
        return preg_replace('/[^a-zA-Z\_]+/', '', $valueToSanitise);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getEmail(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-z\-_0-9\.+@]+/', '', $valueToSanitise);
    }

    /**
     * @deprecated 1.1.0
     */
    public static function getSHA1(mixed $valueToSanitise): string
    {
        $valueToSanitise = strtolower($valueToSanitise);

        return preg_replace('/[^a-f0-9]+/', '', $valueToSanitise);
    }

    /**
     * Sanitize a single line of text.
     *
     * @param string $text
     *
     * @return string
     */
    public static function sanitiseText(string $text): string {
        $sanitisedText = \trim(\addslashes(\filter_var($text, \FILTER_SANITIZE_SPECIAL_CHARS)));

        return $sanitisedText;
    }

    /**
     * Sanitise an email address.
     *
     * @param string $email
     *
     * @return string
     */
    public static function sanitiseEmail(string $email): string {
        $sanitisedEmail = \trim(\addslashes(\filter_var($email, \FILTER_SANITIZE_EMAIL)));

        return $sanitisedEmail;
    }
}
