<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/PublicationEvent
 *
 * @property-read SchemaTypeList<BroadcastService> $publishedOn         A broadcast service associated with the publication event.
 * @property-read SchemaTypeList<Boolean>          $free                A flag to signal that the item, event, or place is accessible for free.
 * @property-read SchemaTypeList<Boolean>          $isAccessibleForFree A flag to signal that the item, event, or place is accessible for free.
 */
interface PublicationEvent extends Event
{
}
