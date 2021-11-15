<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Blog
 *
 * @property-read SchemaTypeList<BlogPosting> $blogPosts The postings that are part of this blog.
 * @property-read SchemaTypeList<BlogPosting> $blogPost  A posting that is part of this blog.
 * @property-read SchemaTypeList<Text>        $issn      The International Standard Serial Number (ISSN) that identifies this serial publication. You can repeat this property to identify different formats of, or the linking ISSN (ISSN-L) for, this serial publication.
 */
interface Blog extends CreativeWork
{
}
