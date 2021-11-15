<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/DataFeedItem
 *
 * @property-read SchemaTypeList<Date|DateTime> $dateModified The date on which the CreativeWork was most recently modified or when the item's entry was modified within a DataFeed.
 * @property-read SchemaTypeList<DateTime>      $dateDeleted  The datetime the item was removed from the DataFeed.
 * @property-read SchemaTypeList<DateTime|Date> $dateCreated  The date on which the CreativeWork was created or the item was added to a DataFeed.
 * @property-read SchemaTypeList<Thing>         $item         An entity represented by an entry in a list or data feed (e.g. an 'artist' in a list of 'artists')’.
 */
interface DataFeedItem extends Intangible
{
}
