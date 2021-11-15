<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/Permit
 *
 * @property-read SchemaTypeList<Duration>           $validFor       The duration of validity of a permit or similar thing.
 * @property-read SchemaTypeList<Date>               $validUntil     The date when the item is no longer valid.
 * @property-read SchemaTypeList<DateTime>           $validFrom      The date when the item becomes valid.
 * @property-read SchemaTypeList<Organization>       $issuedBy       The organization issuing the ticket or permit.
 * @property-read SchemaTypeList<Service>            $issuedThrough  The service through with the permit was granted.
 * @property-read SchemaTypeList<Audience>           $permitAudience The target audience for this permit.
 * @property-read SchemaTypeList<AdministrativeArea> $validIn        The geographic area where a permit or similar thing is valid.
 */
interface Permit extends Intangible
{
}
