<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/CompoundPriceSpecification
 *
 * @property-read SchemaTypeList<UnitPriceSpecification> $priceComponent This property links to all UnitPriceSpecification nodes that apply in parallel for the CompoundPriceSpecification node.
 */
interface CompoundPriceSpecification extends PriceSpecification
{
}
