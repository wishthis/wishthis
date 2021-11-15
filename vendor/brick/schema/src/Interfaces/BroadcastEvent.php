<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/BroadcastEvent
 *
 * @property-read SchemaTypeList<Text>    $videoFormat      The type of screening or video broadcast used (e.g. IMAX, 3D, SD, HD, etc.).
 * @property-read SchemaTypeList<Boolean> $isLiveBroadcast  True is the broadcast is of a live event.
 * @property-read SchemaTypeList<Event>   $broadcastOfEvent The event being broadcast such as a sporting event or awards ceremony.
 */
interface BroadcastEvent extends PublicationEvent
{
}
