<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/SportsOrganization
 *
 * @property-read SchemaTypeList<Text|URL> $sport A type of sport (e.g. Baseball).
 */
interface SportsOrganization extends Organization
{
}
