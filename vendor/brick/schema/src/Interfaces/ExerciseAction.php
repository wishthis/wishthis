<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/ExerciseAction
 *
 * @property-read SchemaTypeList<Place>                  $course                 A sub property of location. The course where this action was taken.
 * @property-read SchemaTypeList<SportsTeam>             $sportsTeam             A sub property of participant. The sports team that participated on this action.
 * @property-read SchemaTypeList<SportsEvent>            $sportsEvent            A sub property of location. The sports event where this action occurred.
 * @property-read SchemaTypeList<Distance>               $distance               The distance travelled, e.g. exercising or travelling.
 * @property-read SchemaTypeList<Person>                 $opponent               A sub property of participant. The opponent on this action.
 * @property-read SchemaTypeList<SportsActivityLocation> $sportsActivityLocation A sub property of location. The sports activity location where this action occurred.
 * @property-read SchemaTypeList<Place>                  $toLocation             A sub property of location. The final location of the object or the agent after the action.
 * @property-read SchemaTypeList<Place>                  $fromLocation           A sub property of location. The original location of the object or the agent before the action.
 * @property-read SchemaTypeList<Place>                  $exerciseCourse         A sub property of location. The course where this action was taken.
 */
interface ExerciseAction extends PlayAction
{
}
