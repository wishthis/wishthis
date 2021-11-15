<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/AudioObject
 *
 * @property-read SchemaTypeList<Text>             $transcript If this MediaObject is an AudioObject or VideoObject, the transcript of that object.
 * @property-read SchemaTypeList<MediaObject|Text> $caption    The caption for this object. For downloadable machine formats (closed caption, subtitles etc.) use MediaObject and indicate the encodingFormat.
 */
interface AudioObject extends MediaObject
{
}
