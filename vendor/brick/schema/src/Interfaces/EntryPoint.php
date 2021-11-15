<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/EntryPoint
 *
 * @property-read SchemaTypeList<Text>                $urlTemplate       An url template (RFC6570) that will be used to construct the target of the execution of the action.
 * @property-read SchemaTypeList<SoftwareApplication> $actionApplication An application that can complete the request.
 * @property-read SchemaTypeList<SoftwareApplication> $application       An application that can complete the request.
 * @property-read SchemaTypeList<URL|Text>            $actionPlatform    The high level platform(s) where the Action can be performed for the given URL. To specify a specific application or operating system instance, use actionApplication.
 * @property-read SchemaTypeList<Text>                $httpMethod        An HTTP method that specifies the appropriate HTTP method for a request to an HTTP EntryPoint. Values are capitalized strings as used in HTTP.
 * @property-read SchemaTypeList<Text>                $encodingType      The supported encoding type(s) for an EntryPoint request.
 * @property-read SchemaTypeList<Text>                $contentType       The supported content type(s) for an EntryPoint response.
 */
interface EntryPoint extends Intangible
{
}
