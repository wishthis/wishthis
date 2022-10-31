<?php
declare(strict_types = 1);

namespace Gettext;

use RuntimeException;

class GettextTranslator implements TranslatorInterface
{
    /**
     * Detects the current language using the environment variables.
     */
    public function __construct(string $language = null)
    {
        if (!function_exists('gettext')) {
            throw new RuntimeException('This class require the gettext extension for PHP');
        }

        //detects the language environment respecting the priority order
        //http://php.net/manual/en/function.gettext.php#114062
        if (empty($language)) {
            $language = getenv('LANGUAGE') ?: getenv('LC_ALL') ?: getenv('LC_MESSAGES') ?: getenv('LANG');
        }

        if (!empty($language)) {
            $this->setLanguage($language);
        }
    }

    /**
     * Define the current locale.
     */
    public function setLanguage(string $language, int $category = null): self
    {
        if ($category === null) {
            $category = defined('LC_MESSAGES') ? LC_MESSAGES : LC_ALL;
        }

        setlocale($category, $language);
        putenv('LANGUAGE='.$language);

        return $this;
    }

    /**
     * Loads a gettext domain.
     */
    public function loadDomain(string $domain, string $path = null, bool $default = true): self
    {
        bindtextdomain($domain, $path);
        bind_textdomain_codeset($domain, 'UTF-8');

        if ($default) {
            textdomain($domain);
        }

        return $this;
    }

    public function noop(string $original): string
    {
        return $original;
    }

    public function gettext(string $original): string
    {
        return gettext($original);
    }

    public function ngettext(string $original, string $plural, int $value): string
    {
        return ngettext($original, $plural, $value);
    }

    public function dngettext(string $domain, string $original, string $plural, int $value): string
    {
        return dngettext($domain, $original, $plural, $value);
    }

    public function npgettext(string $context, string $original, string $plural, int $value): string
    {
        $message = $context."\x04".$original;
        $translation = ngettext($message, $plural, $value);

        return ($translation === $message) ? $original : $translation;
    }

    public function pgettext(string $context, string $original): string
    {
        $message = $context."\x04".$original;
        $translation = gettext($message);

        return ($translation === $message) ? $original : $translation;
    }

    public function dgettext(string $domain, string $original): string
    {
        return dgettext($domain, $original);
    }

    public function dpgettext(string $domain, string $context, string $original): string
    {
        $message = $context."\x04".$original;
        $translation = dgettext($domain, $message);

        return ($translation === $message) ? $original : $translation;
    }

    public function dnpgettext(string $domain, string $context, string $original, string $plural, int $value): string
    {
        $message = $context."\x04".$original;
        $translation = dngettext($domain, $message, $plural, $value);

        return ($translation === $message) ? $original : $translation;
    }
}
