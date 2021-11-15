<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/TouristAttraction
 *
 * @property-read SchemaTypeList<Audience|Text> $touristType       Attraction suitable for type(s) of tourist. eg. Children, visitors from a particular country, etc.
 * @property-read SchemaTypeList<Text|Language> $availableLanguage A language someone may use with or at the item, service or place. Please use one of the language codes from the IETF BCP 47 standard. See also inLanguage
 */
interface TouristAttraction extends Place
{
}
