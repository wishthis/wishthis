<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/SpeakableSpecification
 *
 * @property-read SchemaTypeList $cssSelector A CSS selector, e.g. of a SpeakableSpecification or WebPageElement. In the latter case, multiple matches within a page can constitute a single conceptual "Web page element".
 * @property-read SchemaTypeList $xpath       An XPath, e.g. of a SpeakableSpecification or WebPageElement. In the latter case, multiple matches within a page can constitute a single conceptual "Web page element".
 */
interface SpeakableSpecification extends Intangible
{
}
