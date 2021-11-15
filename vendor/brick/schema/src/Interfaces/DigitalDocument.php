<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/DigitalDocument
 *
 * @property-read SchemaTypeList<DigitalDocumentPermission> $hasDigitalDocumentPermission A permission related to the access to this document (e.g. permission to read or write an electronic document). For a public document, specify a grantee with an Audience with audienceType equal to "public".
 */
interface DigitalDocument extends CreativeWork
{
}
