<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/SportsEvent
 *
 * @property-read SchemaTypeList<Person|SportsTeam> $awayTeam   The away team in a sports event.
 * @property-read SchemaTypeList<SportsTeam|Person> $homeTeam   The home team in a sports event.
 * @property-read SchemaTypeList<SportsTeam|Person> $competitor A competitor in a sports event.
 */
interface SportsEvent extends Event
{
}
