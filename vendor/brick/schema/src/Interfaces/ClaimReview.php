<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ClaimReview
 *
 * @property-read SchemaTypeList<Text> $claimReviewed A short summary of the specific claims reviewed in a ClaimReview.
 */
interface ClaimReview extends Review
{
}
