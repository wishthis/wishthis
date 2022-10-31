<?php
declare(strict_types = 1);

namespace Gettext;

abstract class TranslatorFunctions
{
    private static $translator;
    private static $formatter;

    public static function register(TranslatorInterface $translator, FormatterInterface $formatter = null): void
    {
        self::$translator = $translator;
        self::$formatter = $formatter ?: new Formatter();

        include_once __DIR__.'/functions.php';
    }

    public static function getTranslator(): TranslatorInterface
    {
        return self::$translator;
    }

    public static function getFormatter(): FormatterInterface
    {
        return self::$formatter;
    }
}
