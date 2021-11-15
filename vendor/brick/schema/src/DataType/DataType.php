<?php

declare(strict_types=1);

namespace Brick\Schema\DataType;

use Brick\Schema\SchemaType;
use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/DataType
 */
class DataType implements SchemaType
{
    /**
     * @var string
     */
    private $number;

    /**
     * DataType constructor.
     *
     * @param string $number
     */
    public function __construct(string $number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->number;
    }

    /**
     * @inheritDoc
     */
    public function includesProperty(string $name) : bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getProperty(string $name) : SchemaTypeList
    {
        return new SchemaTypeList([]);
    }
}
