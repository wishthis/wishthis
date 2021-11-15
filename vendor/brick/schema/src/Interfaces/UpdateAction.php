<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/UpdateAction
 *
 * @property-read SchemaTypeList<Thing> $collection       A sub property of object. The collection target of the action.
 * @property-read SchemaTypeList<Thing> $targetCollection A sub property of object. The collection target of the action.
 */
interface UpdateAction extends Action
{
}
