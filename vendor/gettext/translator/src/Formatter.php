<?php
declare(strict_types = 1);

namespace Gettext;

class Formatter implements FormatterInterface
{
    public function format(string $text, array $args): string
    {
        if (empty($args)) {
            return $text;
        }

        return is_array($args[0]) ? strtr($text, $args[0]) : vsprintf($text, $args);
    }
}
