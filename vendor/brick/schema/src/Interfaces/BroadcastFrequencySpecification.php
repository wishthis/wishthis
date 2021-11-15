<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/BroadcastFrequencySpecification
 *
 * @property-read SchemaTypeList<QuantitativeValue|Number> $broadcastFrequencyValue The frequency in MHz for a particular broadcast.
 */
interface BroadcastFrequencySpecification extends Intangible
{
}
