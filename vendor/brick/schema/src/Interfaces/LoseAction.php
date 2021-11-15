<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/LoseAction
 *
 * @property-read SchemaTypeList<Person> $winner A sub property of participant. The winner of the action.
 */
interface LoseAction extends AchieveAction
{
}
