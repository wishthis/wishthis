<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/SocialMediaPosting
 *
 * @property-read SchemaTypeList<CreativeWork> $sharedContent A CreativeWork such as an image, video, or audio clip shared as part of this posting.
 */
interface SocialMediaPosting extends Article
{
}
