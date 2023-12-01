<?php

namespace ConventionalChangelog\Git\Commit;

use ConventionalChangelog\Helper\Formatter;
use ConventionalChangelog\Type\Stringable;

class Scope implements Stringable
{
    /**
     * @var string
     */
    protected $content;

    public function __construct(?string $content = '')
    {
        $this->content = (string)$content;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Prettify.
     */
    public function toPrettyString(): string
    {
        $string = ucfirst($this->content);
        $string = preg_replace('/[_]+/m', ' ', $string);
        $string = preg_replace('/((?<=\p{Ll})\p{Lu})|((?!\A)\p{Lu}(?>\p{Ll}))/u', ' $0', $string);
        $string = preg_replace('/\.(php|md|json|txt|csv|js)($|\s)/', '', $string);

        return Formatter::clean($string);
    }
}
