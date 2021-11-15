<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/NewsArticle
 *
 * @property-read SchemaTypeList<Text> $printColumn  The number of the column in which the NewsArticle appears in the print edition.
 * @property-read SchemaTypeList<Text> $printEdition The edition of the print product in which the NewsArticle appears.
 * @property-read SchemaTypeList<Text> $printSection If this NewsArticle appears in print, this field indicates the print section in which the article appeared.
 * @property-read SchemaTypeList<Text> $printPage    If this NewsArticle appears in print, this field indicates the name of the page on which the article is found. Please note that this field is intended for the exact page name (e.g. A5, B18).
 * @property-read SchemaTypeList<Text> $dateline     A dateline is a brief piece of text included in news articles that describes where and when the story was written or filed though the date is often omitted. Sometimes only a placename is provided.
 */
interface NewsArticle extends Article
{
}
