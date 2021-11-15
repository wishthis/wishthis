<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ItemList
 *
 * @property-read SchemaTypeList<Thing|Text|ListItem>    $itemListElement For itemListElement values, you can use simple strings (e.g. "Peter", "Paul", "Mary"), existing entities, or use ListItem.
 * @property-read SchemaTypeList<Text|ItemListOrderType> $itemListOrder   Type of ordering (e.g. Ascending, Descending, Unordered).
 * @property-read SchemaTypeList<Integer>                $numberOfItems   The number of items in an ItemList. Note that some descriptions might not fully describe all items in a list (e.g., multi-page pagination); in such cases, the numberOfItems would be for the entire list.
 */
interface ItemList extends Intangible
{
}
