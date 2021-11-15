<?php

declare(strict_types=1);

namespace Brick\Schema;

use LogicException;
use OutOfRangeException;

/**
 * The base class that dynamically created objects will extend.
 *
 * This is an implementation detail that may change at any time and should not be relied on.
 * You should only type-hint things against the interfaces.
 *
 * @internal
 */
abstract class Base
{
    /**
     * The list of schema.org types this object implements.
     *
     * Each property is the short name, without the schema.org prefix, e.g. 'Product' or 'Offer'.
     * This list will always contain at least one type.
     *
     * @var string[]
     */
    private $types;

    /**
     * The list of schema.org properties supported by this instance.
     *
     * Each property is the short name, without the schema.org prefix, e.g. 'name' or 'description'.
     * This list will contain the combination of all properties supported by the types the object implements.
     *
     * @var string[]
     */
    private $properties;

    /**
     * The values of each property, indexed by property name.
     *
     * These values are initialized just-in-time, when a property is first accessed.
     *
     * @var SchemaTypeList[]
     */
    private $values = [];

    /**
     * Base constructor.
     *
     * @param string[] $types      The list of schema.org types this class implements.
     * @param string[] $properties The list of schema.org properties supported by this instance.
     */
    public function __construct(array $types, array $properties)
    {
        $this->types      = $types;
        $this->properties = $properties;
    }

    /**
     * Checks if a property is set.
     *
     * All supported properties will be initialized when first accessed, and are therefore considered set.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name) : bool
    {
        return in_array($name, $this->properties, true);
    }

    /**
     * Retrieves a property value.
     *
     * @param string $name The property name, e.g. 'name' or 'description'.
     *
     * @return SchemaTypeList
     *
     * @throws OutOfRangeException
     */
    public function __get(string $name) : SchemaTypeList
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }

        if (! in_array($name, $this->properties, true)) {
            throw new OutOfRangeException(sprintf(
                'Property "%s" is not available in object of type%s %s',
                $name,
                count($this->types) > 1 ? 's' : '',
                implode(', ', $this->types)
            ));
        }

        return $this->values[$name] = new SchemaTypeList();
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     *
     * @throws LogicException
     */
    public function __set(string $name, $value) : void
    {
        throw new LogicException('This object does not support writable properties.');
    }

    /**
     * @param string $name
     *
     * @return void
     *
     * @throws LogicException
     */
    public function __unset(string $name) : void
    {
        throw new LogicException('This object does not support unsetting properties.');
    }
}
