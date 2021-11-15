<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/HowToSection
 *
 * @property-read SchemaTypeList<CreativeWork|ItemList|Text> $steps A single step item (as HowToStep, text, document, video, etc.) or a HowToSection (originally misnamed 'steps'; 'step' is preferred).
 */
interface HowToSection extends ItemList, ListItem, CreativeWork
{
}
