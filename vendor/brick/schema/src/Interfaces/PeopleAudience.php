<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/PeopleAudience
 *
 * @property-read SchemaTypeList<Number>  $suggestedMaxAge Maximal age recommended for viewing content.
 * @property-read SchemaTypeList<Integer> $requiredMinAge  Audiences defined by a person's minimum age.
 * @property-read SchemaTypeList<Text>    $requiredGender  Audiences defined by a person's gender.
 * @property-read SchemaTypeList<Text>    $suggestedGender The gender of the person or audience.
 * @property-read SchemaTypeList<Integer> $requiredMaxAge  Audiences defined by a person's maximum age.
 * @property-read SchemaTypeList<Number>  $suggestedMinAge Minimal age recommended for viewing content.
 */
interface PeopleAudience extends Audience
{
}
