<?php

namespace ConventionalChangelog\Git\Commit;

use ConventionalChangelog\Type\Stringable;

class Footer implements Stringable
{
    /**
     * @var string[]
     */
    public const TOKEN_CLOSE_ISSUE = [
        'close',
        'closes',
        'closed',
        'fix',
        'fixes',
        'fixed',
        'resolve',
        'resolves',
        'resolved',
    ];

    /**
     * Token.
     *
     * @var string
     */
    protected $token;

    /**
     * Value.
     *
     * @var string
     */
    protected $value;

    /**
     * References.
     *
     * @var Reference[]
     */
    protected $references = [];

    public function __construct(string $token, string $value)
    {
        $this->token = trim($token);
        $this->value = trim($value);

        $refs = [];
        $tokenLower = strtolower($this->token);
        $values = preg_split('/[,\s]+/', $this->value, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($values as $val) {
            if (isset($val[0]) && $val[0] === '#') {
                $ref = ltrim($val, '#');
                $obj = new Reference($ref);
                if (in_array($tokenLower, self::TOKEN_CLOSE_ISSUE)) {
                    $obj->setClosed(true);
                }
                $refs[] = $obj;
            }
        }

        $this->setReferences($refs);
    }

    public function __toString(): string
    {
        return $this->token . ': ' . $this->value;
    }

    public function getToken(): string
    {
        return strtolower($this->token);
    }

    public function getValue(): string
    {
        return ucfirst($this->value);
    }

    /**
     * Set issues references.
     *
     * @return Reference[]
     */
    public function setReferences(array $references): self
    {
        $this->references = array_unique($references);

        return $this;
    }

    /**
     * Get issues references.
     *
     * @return Reference[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }
}
