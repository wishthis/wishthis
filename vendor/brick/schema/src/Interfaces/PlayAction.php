<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/PlayAction
 *
 * @property-read SchemaTypeList<Audience> $audience An intended audience, i.e. a group for whom something was created.
 * @property-read SchemaTypeList<Event>    $event    Upcoming or past event associated with this place, organization, or action.
 */
interface PlayAction extends Action
{
}
