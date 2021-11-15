<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/MovieTheater
 *
 * @property-read SchemaTypeList<Number> $screenCount The number of screens in the movie theater.
 */
interface MovieTheater extends CivicStructure, EntertainmentBusiness
{
}
