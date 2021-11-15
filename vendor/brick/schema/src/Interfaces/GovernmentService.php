<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/GovernmentService
 *
 * @property-read SchemaTypeList<Organization> $serviceOperator The operating organization, if different from the provider.  This enables the representation of services that are provided by an organization, but operated by another organization like a subcontractor.
 */
interface GovernmentService extends Service
{
}
