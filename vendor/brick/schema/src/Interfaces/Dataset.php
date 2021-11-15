<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Dataset
 *
 * @property-read SchemaTypeList<DataCatalog>  $includedInDataCatalog A data catalog which contains this dataset.
 * @property-read SchemaTypeList<DateTime>     $datasetTimeInterval   The range of temporal applicability of a dataset, e.g. for a 2011 census dataset, the year 2011 (in ISO 8601 time interval format).
 * @property-read SchemaTypeList<DataCatalog>  $catalog               A data catalog which contains this dataset.
 * @property-read SchemaTypeList<Text>         $issn                  The International Standard Serial Number (ISSN) that identifies this serial publication. You can repeat this property to identify different formats of, or the linking ISSN (ISSN-L) for, this serial publication.
 * @property-read SchemaTypeList<DataCatalog>  $includedDataCatalog   A data catalog which contains this dataset (this property was previously 'catalog', preferred name is now 'includedInDataCatalog').
 * @property-read SchemaTypeList<DataDownload> $distribution          A downloadable form of this dataset, at a specific location, in a specific format.
 */
interface Dataset extends CreativeWork
{
}
