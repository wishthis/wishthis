<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/WinAction
 *
 * @property-read SchemaTypeList<Person> $loser A sub property of participant. The loser of the action.
 */
interface WinAction extends AchieveAction
{
}
