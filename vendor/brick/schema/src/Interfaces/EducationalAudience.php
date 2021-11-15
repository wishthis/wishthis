<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/EducationalAudience
 *
 * @property-read SchemaTypeList<Text> $educationalRole An educationalRole of an EducationalAudience.
 */
interface EducationalAudience extends Audience
{
}
