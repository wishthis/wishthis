<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/VoteAction
 *
 * @property-read SchemaTypeList<Person> $candidate A sub property of object. The candidate subject of this action.
 */
interface VoteAction extends ChooseAction
{
}
