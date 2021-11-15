<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/EducationalOrganization
 *
 * @property-read SchemaTypeList<Person> $alumni Alumni of an organization.
 */
interface EducationalOrganization extends Organization
{
}
