<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/GameServer
 *
 * @property-read SchemaTypeList<VideoGame>        $game          Video game which is played on this server.
 * @property-read SchemaTypeList<Integer>          $playersOnline Number of players on the server.
 * @property-read SchemaTypeList<GameServerStatus> $serverStatus  Status of a game server.
 */
interface GameServer extends Intangible
{
}
