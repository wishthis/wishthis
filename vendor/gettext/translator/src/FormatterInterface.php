<?php
declare(strict_types = 1);

namespace Gettext;

/**
 * Interface used by formatters.
 */
interface FormatterInterface
{
    public function format(string $text, array $args): string;
}
