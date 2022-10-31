<?php
declare(strict_types = 1);

namespace Gettext;

/**
 * Interface used by all translators.
 */
interface TranslatorInterface
{
    /**
     * Noop, marks the string for translation but returns it unchanged.
     */
    public function noop(string $original): string;

    /**
     * Gets a translation using the original string.
     */
    public function gettext(string $original): string;

    /**
     * Gets a translation checking the plural form.
     */
    public function ngettext(string $original, string $plural, int $value): string;

    /**
     * Gets a translation checking the domain and the plural form.
     */
    public function dngettext(string $domain, string $original, string $plural, int $value): string;

    /**
     * Gets a translation checking the context and the plural form.
     */
    public function npgettext(string $context, string $original, string $plural, int $value): string;

    /**
     * Gets a translation checking the context.
     */
    public function pgettext(string $context, string $original): string;

    /**
     * Gets a translation checking the domain.
     */
    public function dgettext(string $domain, string $original): string;

    /**
     * Gets a translation checking the domain and context.
     */
    public function dpgettext(string $domain, string $context, string $original): string;

    /**
     * Gets a translation checking the domain, the context and the plural form.
     */
    public function dnpgettext(string $domain, string $context, string $original, string $plural, int $value);
}
