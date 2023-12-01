<?php

namespace ConventionalChangelog\Git\Commit;

use ConventionalChangelog\Type\Stringable;

class Reference implements Stringable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $closed = false;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return '#' . $this->id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function setClosed(bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }
}
