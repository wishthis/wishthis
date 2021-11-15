<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/MobileApplication
 *
 * @property-read SchemaTypeList<Text> $carrierRequirements Specifies specific carrier(s) requirements for the application (e.g. an application may only work on a specific carrier network).
 */
interface MobileApplication extends SoftwareApplication
{
}
