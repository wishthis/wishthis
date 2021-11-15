<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Rating
 *
 * @property-read SchemaTypeList<Number|Text>         $ratingValue  The rating for the content.
 * @property-read SchemaTypeList<Text|Number>         $bestRating   The highest value allowed in this rating system. If bestRating is omitted, 5 is assumed.
 * @property-read SchemaTypeList<Organization|Person> $author       The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably.
 * @property-read SchemaTypeList<Text|Number>         $worstRating  The lowest value allowed in this rating system. If worstRating is omitted, 1 is assumed.
 * @property-read SchemaTypeList<Text>                $reviewAspect This Review or Rating is relevant to this part or facet of the itemReviewed.
 */
interface Rating extends Intangible
{
}
