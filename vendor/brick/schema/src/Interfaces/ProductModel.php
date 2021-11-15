<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/ProductModel
 *
 * @property-read SchemaTypeList<ProductModel> $predecessorOf A pointer from a previous, often discontinued variant of the product to its newer variant.
 * @property-read SchemaTypeList<ProductModel> $successorOf   A pointer from a newer variant of a product  to its previous, often discontinued predecessor.
 * @property-read SchemaTypeList<ProductModel> $isVariantOf   A pointer to a base product from which this product is a variant. It is safe to infer that the variant inherits all product features from the base model, unless defined locally. This is not transitive.
 */
interface ProductModel extends Product
{
}
