<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/MediaObject
 *
 * @property-read SchemaTypeList<DateTime>                   $startTime            The startTime of something. For a reserved event or service (e.g. FoodEstablishmentReservation), the time that it is expected to start. For actions that span a period of time, when the action was performed. e.g. John wrote a book from January to December. For media, including audio and video, it's the time offset of the start of a clip within a larger file.
 * @property-read SchemaTypeList<Date>                       $uploadDate           Date when this media object was uploaded to this site.
 * @property-read SchemaTypeList<Text>                       $playerType           Player type required—for example, Flash or Silverlight.
 * @property-read SchemaTypeList<QuantitativeValue|Distance> $height               The height of the item.
 * @property-read SchemaTypeList<Text>                       $bitrate              The bitrate of the media object.
 * @property-read SchemaTypeList<MediaSubscription|Boolean>  $requiresSubscription Indicates if use of the media require a subscription  (either paid or free). Allowed values are true or false (note that an earlier version had 'yes', 'no').
 * @property-read SchemaTypeList<Place>                      $regionsAllowed       The regions where the media is allowed. If not specified, then it's assumed to be allowed everywhere. Specify the countries in ISO 3166 format.
 * @property-read SchemaTypeList<DateTime>                   $endTime              The endTime of something. For a reserved event or service (e.g. FoodEstablishmentReservation), the time that it is expected to end. For actions that span a period of time, when the action was performed. e.g. John wrote a book from January to December. For media, including audio and video, it's the time offset of the end of a clip within a larger file.
 * @property-read SchemaTypeList<Text>                       $contentSize          File size in (mega/kilo) bytes.
 * @property-read SchemaTypeList<URL>                        $embedUrl             A URL pointing to a player for a specific video. In general, this is the information in the src element of an embed tag and should not be the same as the content of the loc tag.
 * @property-read SchemaTypeList<Distance|QuantitativeValue> $width                The width of the item.
 * @property-read SchemaTypeList<URL>                        $contentUrl           Actual bytes of the media object, for example the image file or video file.
 * @property-read SchemaTypeList<NewsArticle>                $associatedArticle    A NewsArticle associated with the Media Object.
 * @property-read SchemaTypeList<Organization>               $productionCompany    The production company or studio responsible for the item e.g. series, video game, episode etc.
 * @property-read SchemaTypeList<Duration>                   $duration             The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
 * @property-read SchemaTypeList<CreativeWork>               $encodesCreativeWork  The CreativeWork encoded by this media object.
 * @property-read SchemaTypeList<URL|Text>                   $encodingFormat       Media type typically expressed using a MIME format (see IANA site and MDN reference) e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc.).
 */
interface MediaObject extends CreativeWork
{
}
