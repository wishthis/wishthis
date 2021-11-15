<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Course
 *
 * @property-read SchemaTypeList<AlignmentObject|Text|Course> $coursePrerequisites          Requirements for taking the Course. May be completion of another Course or a textual description like "permission of instructor". Requirements may be a pre-requisite competency, referenced using AlignmentObject.
 * @property-read SchemaTypeList<Text|URL>                    $educationalCredentialAwarded A description of the qualification, award, certificate, diploma or other educational credential awarded as a consequence of successful completion of this course.
 * @property-read SchemaTypeList<Text>                        $courseCode                   The identifier for the Course used by the course provider (e.g. CS101 or 6.001).
 * @property-read SchemaTypeList<CourseInstance>              $hasCourseInstance            An offering of the course at a specific time and place or through specific media or mode of study or to a specific section of students.
 */
interface Course extends CreativeWork
{
}
