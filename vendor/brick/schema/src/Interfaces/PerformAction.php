<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/PerformAction
 *
 * @property-read SchemaTypeList<EntertainmentBusiness> $entertainmentBusiness A sub property of location. The entertainment business where the action occurred.
 */
interface PerformAction extends PlayAction
{
}
