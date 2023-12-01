<?php

namespace ConventionalChangelog\Git\Commit;

use ConventionalChangelog\Type\Stringable;

class Type implements Stringable
{
    /**
     * @var string
     */
    protected $content;

    public function __construct(string $content)
    {
        $this->content = strtolower($content);
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
