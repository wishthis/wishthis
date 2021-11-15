<?php

declare(strict_types=1);

namespace Brick\Schema;

interface SchemaType
{
    /**
     * Whether this type includes the given property.
     *
     * @param string $name The name of the property. Example: 'brand'.
     *
     * @return bool
     */
    public function includesProperty(string $name) : bool;

    /**
     * Returns the value(s) for the given property.
     *
     * @param string $name The name of the property. Example: 'brand'.
     *
     * @return SchemaTypeList
     */
    public function getProperty(string $name) : SchemaTypeList;
}
