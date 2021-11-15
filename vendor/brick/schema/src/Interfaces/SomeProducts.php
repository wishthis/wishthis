<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/SomeProducts
 *
 * @property-read SchemaTypeList<QuantitativeValue> $inventoryLevel The current approximate inventory level for the item or items.
 */
interface SomeProducts extends Product
{
}
