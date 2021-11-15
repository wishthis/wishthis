<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/InformAction
 *
 * @property-read SchemaTypeList<Event> $event Upcoming or past event associated with this place, organization, or action.
 */
interface InformAction extends CommunicateAction
{
}
