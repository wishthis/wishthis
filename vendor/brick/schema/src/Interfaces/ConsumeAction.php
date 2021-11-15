<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/ConsumeAction
 *
 * @property-read SchemaTypeList<ActionAccessSpecification> $actionAccessibilityRequirement A set of requirements that a must be fulfilled in order to perform an Action. If more than one value is specied, fulfilling one set of requirements will allow the Action to be performed.
 * @property-read SchemaTypeList<Offer>                     $expectsAcceptanceOf            An Offer which must be accepted before the user can perform the Action. For example, the user may need to buy a movie before being able to watch it.
 */
interface ConsumeAction extends Action
{
}
