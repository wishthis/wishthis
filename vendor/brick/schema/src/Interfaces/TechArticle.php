<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/TechArticle
 *
 * @property-read SchemaTypeList<Text> $proficiencyLevel Proficiency needed for this content; expected values: 'Beginner', 'Expert'.
 * @property-read SchemaTypeList<Text> $dependencies     Prerequisites needed to fulfill steps in article.
 */
interface TechArticle extends Article
{
}
