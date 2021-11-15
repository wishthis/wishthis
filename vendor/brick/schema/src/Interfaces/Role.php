<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Role
 *
 * @property-read SchemaTypeList<DateTime|Date> $endDate       The end date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<Text|URL>      $namedPosition A position played, performed or filled by a person or organization, as part of an organization. For example, an athlete in a SportsTeam might play in the position named 'Quarterback'.
 * @property-read SchemaTypeList<Date|DateTime> $startDate     The start date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<Text|URL>      $roleName      A role played, performed or filled by a person or organization. For example, the team of creators for a comic book might fill the roles named 'inker', 'penciller', and 'letterer'; or an athlete in a SportsTeam might play in the position named 'Quarterback'.
 */
interface Role extends Intangible
{
}
