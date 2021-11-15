<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;

/**
 * http://schema.org/Product
 *
 * @property-read SchemaTypeList<Organization>               $manufacturer              The manufacturer of the product.
 * @property-read SchemaTypeList<Text>                       $sku                       The Stock Keeping Unit (SKU), i.e. a merchant-specific identifier for a product or service, or the product to which the offer refers.
 * @property-read SchemaTypeList<Date>                       $productionDate            The date of production of the item, e.g. vehicle.
 * @property-read SchemaTypeList<Audience>                   $audience                  An intended audience, i.e. a group for whom something was created.
 * @property-read SchemaTypeList<Text>                       $mpn                       The Manufacturer Part Number (MPN) of the product, or the product to which the offer refers.
 * @property-read SchemaTypeList<QuantitativeValue|Distance> $height                    The height of the item.
 * @property-read SchemaTypeList<Text>                       $gtin8                     The GTIN-8 code of the product, or the product to which the offer refers. This code is also known as EAN/UCC-8 or 8-digit EAN. See GS1 GTIN Summary for more details.
 * @property-read SchemaTypeList<AggregateRating>            $aggregateRating           The overall rating, based on a collection of reviews or ratings, of the item.
 * @property-read SchemaTypeList<Product>                    $isConsumableFor           A pointer to another product (or multiple products) for which this product is a consumable.
 * @property-read SchemaTypeList<Review>                     $reviews                   Review of the item.
 * @property-read SchemaTypeList<Offer>                      $offers                    An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
 * @property-read SchemaTypeList<Text>                       $award                     An award won by or for this item.
 * @property-read SchemaTypeList<Text|Thing>                 $category                  A category for the item. Greater signs or slashes can be used to informally indicate a category hierarchy.
 * @property-read SchemaTypeList<Distance|QuantitativeValue> $width                     The width of the item.
 * @property-read SchemaTypeList<Text>                       $awards                    Awards won by or for this item.
 * @property-read SchemaTypeList<PropertyValue>              $additionalProperty        A property-value pair representing an additional characteristics of the entitity, e.g. a product feature or another characteristic for which there is no matching property in schema.org.
 * @property-read SchemaTypeList<Product>                    $isAccessoryOrSparePartFor A pointer to another product (or multiple products) for which this product is an accessory or spare part.
 * @property-read SchemaTypeList<URL|ImageObject>            $logo                      An associated logo.
 * @property-read SchemaTypeList<Text>                       $gtin14                    The GTIN-14 code of the product, or the product to which the offer refers. See GS1 GTIN Summary for more details.
 * @property-read SchemaTypeList<Text>                       $gtin13                    The GTIN-13 code of the product, or the product to which the offer refers. This is equivalent to 13-digit ISBN codes and EAN UCC-13. Former 12-digit UPC codes can be converted into a GTIN-13 code by simply adding a preceeding zero. See GS1 GTIN Summary for more details.
 * @property-read SchemaTypeList<Text>                       $gtin12                    The GTIN-12 code of the product, or the product to which the offer refers. The GTIN-12 is the 12-digit GS1 Identification Key composed of a U.P.C. Company Prefix, Item Reference, and Check Digit used to identify trade items. See GS1 GTIN Summary for more details.
 * @property-read SchemaTypeList<Text|URL|Product>           $material                  A material that something is made from, e.g. leather, wool, cotton, paper.
 * @property-read SchemaTypeList<QuantitativeValue>          $weight                    The weight of the product or person.
 * @property-read SchemaTypeList<Product|Service>            $isSimilarTo               A pointer to another, functionally similar product (or multiple products).
 * @property-read SchemaTypeList<ProductModel|Text>          $model                     The model of the product. Use with the URL of a ProductModel or a textual representation of the model identifier. The URL of the ProductModel can be from an external source. It is recommended to additionally provide strong product identifiers via the gtin8/gtin13/gtin14 and mpn properties.
 * @property-read SchemaTypeList<Text>                       $color                     The color of the product.
 * @property-read SchemaTypeList<Service|Product>            $isRelatedTo               A pointer to another, somehow related product (or multiple products).
 * @property-read SchemaTypeList<Text>                       $productID                 The product identifier, such as ISBN. For example: meta itemprop="productID" content="isbn:123-456-789".
 * @property-read SchemaTypeList<Review>                     $review                    A review of the item.
 * @property-read SchemaTypeList<QuantitativeValue|Distance> $depth                     The depth of the item.
 * @property-read SchemaTypeList<Date>                       $purchaseDate              The date the item e.g. vehicle was purchased by the current owner.
 * @property-read SchemaTypeList<OfferItemCondition>         $itemCondition             A predefined value from OfferItemCondition or a textual description of the condition of the product or service, or the products or services included in the offer.
 * @property-read SchemaTypeList<Text>                       $slogan                    A slogan or motto associated with the item.
 * @property-read SchemaTypeList<Organization|Brand>         $brand                     The brand(s) associated with a product or service, or the brand(s) maintained by an organization or business person.
 * @property-read SchemaTypeList<Date>                       $releaseDate               The release date of a product or product model. This can be used to distinguish the exact variant of a product.
 */
interface Product extends Thing
{
}
