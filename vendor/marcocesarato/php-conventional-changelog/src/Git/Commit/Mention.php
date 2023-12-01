<?php

namespace ConventionalChangelog\Git\Commit;

use ConventionalChangelog\Type\Stringable;

class Mention implements Stringable
{
    /**
     * @var string
     */
    protected $user;

    public function __construct(string $user)
    {
        $this->user = $user;
    }

    public function __toString(): string
    {
        return '@' . $this->user;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
