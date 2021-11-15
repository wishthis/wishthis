<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/UserComments
 *
 * @property-read SchemaTypeList<Text>                $commentText The text of the UserComment.
 * @property-read SchemaTypeList<CreativeWork>        $discusses   Specifies the CreativeWork associated with the UserComment.
 * @property-read SchemaTypeList<Date|DateTime>       $commentTime The time at which the UserComment was made.
 * @property-read SchemaTypeList<Person|Organization> $creator     The creator/author of this CreativeWork. This is the same as the Author property for CreativeWork.
 * @property-read SchemaTypeList<URL>                 $replyToUrl  The URL at which a reply may be posted to the specified UserComment.
 */
interface UserComments extends UserInteraction
{
}
