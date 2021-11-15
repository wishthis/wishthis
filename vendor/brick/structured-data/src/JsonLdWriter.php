<?php

declare(strict_types=1);

namespace Brick\StructuredData;

/**
 * Exports Items to JSON-LD.
 */
class JsonLdWriter
{
    /**
     * Exports a list of Items as JSON-LD.
     *
     * @param Item ...$items
     *
     * @return string The JSON-LD representation.
     */
    public function write(Item ...$items) : string
    {
        $items = array_map(function(Item $item) {
            return $this->convertItem($item);
        }, $items);

        return json_encode($this->extractIfSingle($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Converts an Item to an associative array that can be encoded with json_encode().
     *
     * @param Item $item
     *
     * @return array
     */
    private function convertItem(Item $item) : array
    {
        $result = [
            '@type' => $this->extractIfSingle($item->getTypes())
        ];

        if ($item->getId() !== null) {
            $result['@id'] = $item->getId();
        }

        foreach ($item->getProperties() as $name => $values) {
            foreach ($values as $key => $value) {
                if ($value instanceof Item) {
                    $values[$key] = $this->convertItem($value);
                }
            }

            $result[$name] = $this->extractIfSingle($values);
        }

        return $result;
    }

    /**
     * Returns the value from a list containing a single value, or the array if it does not contain exactly one value.
     *
     * @param array $values
     *
     * @return mixed
     */
    private function extractIfSingle(array $values)
    {
        if (count($values) === 1) {
            return $values[0];
        }

        return $values;
    }
}
