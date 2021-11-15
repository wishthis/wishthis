<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/HowToSupply
 *
 * @property-read SchemaTypeList<Text|MonetaryAmount> $estimatedCost The estimated cost of the supply or supplies consumed when performing instructions.
 */
interface HowToSupply extends HowToItem
{
}
