<?php

namespace ConventionalChangelog\Type;

/**
 * Convertible to string.
 *
 * @internal
 */
interface Stringable
{
    public function __toString(): string;
}
