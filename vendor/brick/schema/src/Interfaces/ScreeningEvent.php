<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ScreeningEvent
 *
 * @property-read SchemaTypeList<Text>          $videoFormat      The type of screening or video broadcast used (e.g. IMAX, 3D, SD, HD, etc.).
 * @property-read SchemaTypeList<Language|Text> $subtitleLanguage Languages in which subtitles/captions are available, in IETF BCP 47 standard format.
 * @property-read SchemaTypeList<Movie>         $workPresented    The movie presented during this event.
 */
interface ScreeningEvent extends Event
{
}
