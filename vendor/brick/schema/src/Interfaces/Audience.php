<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Audience
 *
 * @property-read SchemaTypeList<Text>               $audienceType   The target group associated with a given audience (e.g. veterans, car owners, musicians, etc.).
 * @property-read SchemaTypeList<AdministrativeArea> $geographicArea The geographic area associated with the audience.
 */
interface Audience extends Intangible
{
}
