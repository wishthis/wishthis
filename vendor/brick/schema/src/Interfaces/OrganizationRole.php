<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/OrganizationRole
 *
 * @property-read SchemaTypeList<Number> $numberedPosition A number associated with a role in an organization, for example, the number on an athlete's jersey.
 */
interface OrganizationRole extends Role
{
}
