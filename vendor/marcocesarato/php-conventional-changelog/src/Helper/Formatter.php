<?php

namespace ConventionalChangelog\Helper;

class Formatter
{
    /**
     * Clean string removing double spaces and trimming.
     */
    public static function clean(string $string): string
    {
        $string = trim($string);

        return preg_replace('/[[:blank:]]+/m', ' ', $string);
    }
}
