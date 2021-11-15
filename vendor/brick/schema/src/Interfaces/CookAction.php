<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/CookAction
 *
 * @property-read SchemaTypeList<FoodEstablishment|Place> $foodEstablishment A sub property of location. The specific food establishment where the action occurred.
 * @property-read SchemaTypeList<Recipe>                  $recipe            A sub property of instrument. The recipe/instructions used to perform the action.
 * @property-read SchemaTypeList<FoodEvent>               $foodEvent         A sub property of location. The specific food event where the action occurred.
 */
interface CookAction extends CreateAction
{
}
