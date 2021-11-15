<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/HowToItem
 *
 * @property-read SchemaTypeList<Number|Text|QuantitativeValue> $requiredQuantity The required quantity of the item(s).
 */
interface HowToItem extends ListItem
{
}
