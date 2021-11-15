<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/ParentAudience
 *
 * @property-read SchemaTypeList<Number> $childMaxAge Maximal age of the child.
 * @property-read SchemaTypeList<Number> $childMinAge Minimal age of the child.
 */
interface ParentAudience extends PeopleAudience
{
}
