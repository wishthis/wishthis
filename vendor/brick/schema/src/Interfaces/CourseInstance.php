<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/CourseInstance
 *
 * @property-read SchemaTypeList<Person>   $instructor A person assigned to instruct or provide instructional assistance for the CourseInstance.
 * @property-read SchemaTypeList<URL|Text> $courseMode The medium or means of delivery of the course instance or the mode of study, either as a text label (e.g. "online", "onsite" or "blended"; "synchronous" or "asynchronous"; "full-time" or "part-time") or as a URL reference to a term from a controlled vocabulary (e.g. https://ceds.ed.gov/element/001311#Asynchronous ).
 */
interface CourseInstance extends Event
{
}
