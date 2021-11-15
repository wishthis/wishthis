<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Review
 *
 * @property-read SchemaTypeList<Rating> $reviewRating The rating given in this review. Note that reviews can themselves be rated. The reviewRating applies to rating given by the review. The aggregateRating property applies to the review itself, as a creative work.
 * @property-read SchemaTypeList<Thing>  $itemReviewed The item that is being reviewed/rated.
 * @property-read SchemaTypeList<Text>   $reviewBody   The actual body of the review.
 * @property-read SchemaTypeList<Text>   $reviewAspect This Review or Rating is relevant to this part or facet of the itemReviewed.
 */
interface Review extends CreativeWork
{
}
