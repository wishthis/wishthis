<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ChooseAction
 *
 * @property-read SchemaTypeList<Text|Thing> $actionOption A sub property of object. The options subject to this action.
 * @property-read SchemaTypeList<Text|Thing> $option       A sub property of object. The options subject to this action.
 */
interface ChooseAction extends AssessAction
{
}
