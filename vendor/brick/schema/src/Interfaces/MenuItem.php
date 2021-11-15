<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/MenuItem
 *
 * @property-read SchemaTypeList<MenuItem|MenuSection> $menuAddOn       Additional menu item(s) such as a side dish of salad or side order of fries that can be added to this menu item. Additionally it can be a menu section containing allowed add-on menu items for this menu item.
 * @property-read SchemaTypeList<NutritionInformation> $nutrition       Nutrition information about the recipe or menu item.
 * @property-read SchemaTypeList<Offer>                $offers          An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
 * @property-read SchemaTypeList<RestrictedDiet>       $suitableForDiet Indicates a dietary restriction or guideline for which this recipe or menu item is suitable, e.g. diabetic, halal etc.
 */
interface MenuItem extends Intangible
{
}
