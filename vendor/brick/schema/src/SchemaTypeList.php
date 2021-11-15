<?php

declare(strict_types=1);

namespace Brick\Schema;

use Brick\Schema\Interfaces\Thing;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class SchemaTypeList implements Countable, IteratorAggregate
{
    /**
     * @var array<Thing|string>
     */
    private $values = [];

    /**
     * @param Thing|string $value
     */
    public function addValue($value) : void
    {
        $this->values[] = $value;
    }

    /**
     * Returns all the values in this list.
     *
     * @return array<Thing|string>
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Returns the first value in this list, or null if empty.
     *
     * @return Thing|string|null
     */
    public function getFirstValue()
    {
        return $this->values[0] ?? null;
    }

    /**
     * Returns the first non-empty trimmed string from the list, or null if none.
     *
     * @return string|null
     */
    public function getFirstNonEmptyStringValue() : ?string
    {
        foreach ($this->values as $value) {
            if (is_string($value)) {
                $value = trim($value);

                if ($value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * Attempts to convert this list to a string.
     *
     * If this list contains at least one value, and the first one is a string, this one is returned.
     * If the list is empty, or the first value is not a string, null is returned.
     *
     * @return string|null
     */
    public function toString() : ?string
    {
        if (isset($this->values[0]) && is_string($this->values[0])) {
            return $this->values[0];
        }

        return null;
    }

    /**
     * Returns the number of values in this list.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->values);
    }

    /**
     * Makes the object iterable.
     *
     * @return ArrayIterator<Thing|string>
     */
    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->values);
    }

    /**
     * Converts this list to a string.
     *
     * The logic is slightly different from toString().
     *
     * If this list contains at least one string, the first string value will be returned.
     * If this list does not contain any string, an empty string is returned.
     *
     * @return string
     */
    public function __toString() : string
    {
        foreach ($this->values as $value) {
            if (is_string($value)) {
                return $value;
            }
        }

        return '';
    }
}
