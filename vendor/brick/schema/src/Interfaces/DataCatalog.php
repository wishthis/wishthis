<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/DataCatalog
 *
 * @property-read SchemaTypeList<Dataset> $dataset A dataset contained in this catalog.
 */
interface DataCatalog extends CreativeWork
{
}
