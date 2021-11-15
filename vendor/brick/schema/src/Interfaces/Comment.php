<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/Comment
 *
 * @property-read SchemaTypeList<Integer>  $upvoteCount   The number of upvotes this question, answer or comment has received from the community.
 * @property-read SchemaTypeList<Question> $parentItem    The parent of a question, answer or item in general.
 * @property-read SchemaTypeList<Integer>  $downvoteCount The number of downvotes this question, answer or comment has received from the community.
 */
interface Comment extends CreativeWork
{
}
