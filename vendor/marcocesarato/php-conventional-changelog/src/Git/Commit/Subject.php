<?php

namespace ConventionalChangelog\Git\Commit;

use ConventionalChangelog\Type\Stringable;

class Subject implements Stringable
{
    /**
     * @var string
     */
    protected $content = '';

    public function __construct(?string $content = '')
    {
        $this->content = (string)$content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
