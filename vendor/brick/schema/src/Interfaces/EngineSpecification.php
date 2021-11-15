<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/EngineSpecification
 *
 * @property-read SchemaTypeList<QualitativeValue|URL|Text> $fuelType The type of fuel suitable for the engine or engines of the vehicle. If the vehicle has only one engine, this property can be attached directly to the vehicle.
 */
interface EngineSpecification extends StructuredValue
{
}
