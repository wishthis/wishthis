<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/ImageObject
 *
 * @property-read SchemaTypeList<PropertyValue|Text> $exifData             exif data for this object.
 * @property-read SchemaTypeList<Boolean>            $representativeOfPage Indicates whether this image is representative of the content of the page.
 * @property-read SchemaTypeList<ImageObject>        $thumbnail            Thumbnail image for an image or video.
 * @property-read SchemaTypeList<MediaObject|Text>   $caption              The caption for this object. For downloadable machine formats (closed caption, subtitles etc.) use MediaObject and indicate the encodingFormat.
 */
interface ImageObject extends MediaObject
{
}
