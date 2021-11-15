<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/Game
 *
 * @property-read SchemaTypeList<Thing>                   $gameItem           An item is an object within the game world that can be collected by a player or, occasionally, a non-player character.
 * @property-read SchemaTypeList<Thing>                   $characterAttribute A piece of data that represents a particular aspect of a fictional character (skill, power, character points, advantage, disadvantage).
 * @property-read SchemaTypeList<URL|Place|PostalAddress> $gameLocation       Real or fictional location of the game (or part of game).
 * @property-read SchemaTypeList<Thing>                   $quest              The task that a player-controlled character, or group of characters may complete in order to gain a reward.
 * @property-read SchemaTypeList<QuantitativeValue>       $numberOfPlayers    Indicate how many people can play this game (minimum, maximum, or range).
 */
interface Game extends CreativeWork
{
}
