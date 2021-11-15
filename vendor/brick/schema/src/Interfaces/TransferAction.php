<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/TransferAction
 *
 * @property-read SchemaTypeList<Place> $toLocation   A sub property of location. The final location of the object or the agent after the action.
 * @property-read SchemaTypeList<Place> $fromLocation A sub property of location. The original location of the object or the agent before the action.
 */
interface TransferAction extends Action
{
}
