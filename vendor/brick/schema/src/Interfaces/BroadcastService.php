<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/BroadcastService
 *
 * @property-read SchemaTypeList<Text|BroadcastFrequencySpecification> $broadcastFrequency   The frequency used for over-the-air broadcasts. Numeric values or simple ranges e.g. 87-99. In addition a shortcut idiom is supported for frequences of AM and FM radio channels, e.g. "87 FM".
 * @property-read SchemaTypeList<Text>                                 $broadcastTimezone    The timezone in ISO 8601 format for which the service bases its broadcasts
 * @property-read SchemaTypeList<Text>                                 $videoFormat          The type of screening or video broadcast used (e.g. IMAX, 3D, SD, HD, etc.).
 * @property-read SchemaTypeList<BroadcastService>                     $parentService        A broadcast service to which the broadcast service may belong to such as regional variations of a national channel.
 * @property-read SchemaTypeList<Text>                                 $broadcastDisplayName The name displayed in the channel guide. For many US affiliates, it is the network name.
 * @property-read SchemaTypeList<Organization>                         $broadcastAffiliateOf The media network(s) whose content is broadcast on this station.
 * @property-read SchemaTypeList<Place>                                $area                 The area within which users can expect to reach the broadcast service.
 * @property-read SchemaTypeList<BroadcastChannel>                     $hasBroadcastChannel  A broadcast channel of a broadcast service.
 * @property-read SchemaTypeList<Organization>                         $broadcaster          The organization owning or operating the broadcast service.
 */
interface BroadcastService extends Service
{
}
