<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/LiveBlogPosting
 *
 * @property-read SchemaTypeList<DateTime>    $coverageStartTime The time when the live blog will begin covering the Event. Note that coverage may begin before the Event's start time. The LiveBlogPosting may also be created before coverage begins.
 * @property-read SchemaTypeList<DateTime>    $coverageEndTime   The time when the live blog will stop covering the Event. Note that coverage may continue after the Event concludes.
 * @property-read SchemaTypeList<BlogPosting> $liveBlogUpdate    An update to the LiveBlog.
 */
interface LiveBlogPosting extends BlogPosting
{
}
