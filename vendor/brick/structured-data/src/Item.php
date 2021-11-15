<?php

declare(strict_types=1);

namespace Brick\StructuredData;

use TypeError;

/**
 * An item, such as a Thing in schema.org's vocabulary.
 */
class Item
{
    /**
     * The global identifier of the item, if any.
     *
     * @var string|null
     */
    private $id;

    /**
     * The types this Item implements, as URLs.
     *
     * @var array<string>
     */
    private $types;

    /**
     * The properties, as a map of property name to list of values.
     *
     * @var array<string, array<Item|string>>
     */
    private $properties = [];

    /**
     * Item constructor.
     *
     * @param string|null $id       An optional global identifier for the item.
     * @param string      ...$types The types this Item implements, as URLs, e.g. http://schema.org/Product .
     */
    public function __construct(?string $id, string ...$types)
    {
        $this->id    = $id;
        $this->types = $types;
    }

    /**
     * Returns the global identifier of the item, if any.
     *
     * @return string|null
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * Returns the list of types this Item implements.
     *
     * Each type is a represented as a URL, e.g. http://schema.org/Product .
     *
     * @return array<string>
     */
    public function getTypes() : array
    {
        return $this->types;
    }

    /**
     * Returns a map of property name to list of values.
     *
     * Property names are represented as URLs, e.g. http://schema.org/price .
     * Values are a list of Item instances or plain strings.
     *
     * @return array<string, array<Item|string>>
     */
    public function getProperties() : array
    {
        return $this->properties;
    }

    /**
     * Returns a list of values for the given property.
     *
     * The result is a list of Item instances or plain strings.
     * If the property does not exist, an empty array is returned.
     *
     * @param string $name
     *
     * @return array<Item|string>
     */
    public function getProperty(string $name) : array
    {
        return $this->properties[$name] ?? [];
    }

    /**
     * @param string      $name
     * @param Item|string $value
     *
     * @return void
     */
    public function addProperty(string $name, $value) : void
    {
        if (! $value instanceof Item && ! is_string($value)) {
            throw new TypeError(sprintf('Property value must be an instance of %s or a string.', Item::class));
        }

        $this->properties[$name][] = $value;
    }
}
