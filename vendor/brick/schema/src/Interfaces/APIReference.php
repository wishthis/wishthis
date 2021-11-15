<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/APIReference
 *
 * @property-read SchemaTypeList<Text> $executableLibraryName Library file name e.g., mscorlib.dll, system.web.dll.
 * @property-read SchemaTypeList<Text> $assemblyVersion       Associated product/technology version. e.g., .NET Framework 4.5.
 * @property-read SchemaTypeList<Text> $programmingModel      Indicates whether API is managed or unmanaged.
 * @property-read SchemaTypeList<Text> $assembly              Library file name e.g., mscorlib.dll, system.web.dll.
 * @property-read SchemaTypeList<Text> $targetPlatform        Type of app development: phone, Metro style, desktop, XBox, etc.
 */
interface APIReference extends TechArticle
{
}
