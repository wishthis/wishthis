<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/CreativeWorkSeries
 *
 * @property-read SchemaTypeList<DateTime|Date> $endDate   The end date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<Text>          $issn      The International Standard Serial Number (ISSN) that identifies this serial publication. You can repeat this property to identify different formats of, or the linking ISSN (ISSN-L) for, this serial publication.
 * @property-read SchemaTypeList<Date|DateTime> $startDate The start date and time of the item (in ISO 8601 date format).
 */
interface CreativeWorkSeries extends Series, CreativeWork
{
}
