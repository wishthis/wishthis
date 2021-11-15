<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/SportsTeam
 *
 * @property-read SchemaTypeList<Person> $athlete A person that acts as performing member of a sports team; a player as opposed to a coach.
 * @property-read SchemaTypeList<Person> $coach   A person that acts in a coaching role for a sports team.
 */
interface SportsTeam extends SportsOrganization
{
}
