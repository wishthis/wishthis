<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/InviteAction
 *
 * @property-read SchemaTypeList<Event> $event Upcoming or past event associated with this place, organization, or action.
 */
interface InviteAction extends CommunicateAction
{
}
