<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/EndorseAction
 *
 * @property-read SchemaTypeList<Person|Organization> $endorsee A sub property of participant. The person/organization being supported.
 */
interface EndorseAction extends ReactAction
{
}
