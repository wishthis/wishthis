<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/VisualArtwork
 *
 * @property-read SchemaTypeList<QuantitativeValue|Distance> $height         The height of the item.
 * @property-read SchemaTypeList<Text|URL>                   $artMedium      The material used. (e.g. Oil, Watercolour, Acrylic, Linoprint, Marble, Cyanotype, Digital, Lithograph, DryPoint, Intaglio, Pastel, Woodcut, Pencil, Mixed Media, etc.)
 * @property-read SchemaTypeList<Text|URL>                   $artform        e.g. Painting, Drawing, Sculpture, Print, Photograph, Assemblage, Collage, etc.
 * @property-read SchemaTypeList<URL|Text>                   $artworkSurface The supporting materials for the artwork, e.g. Canvas, Paper, Wood, Board, etc.
 * @property-read SchemaTypeList<Text|Integer>               $artEdition     The number of copies when multiple copies of a piece of artwork are produced - e.g. for a limited edition of 20 prints, 'artEdition' refers to the total number of copies (in this example "20").
 * @property-read SchemaTypeList<Distance|QuantitativeValue> $width          The width of the item.
 * @property-read SchemaTypeList<Text|URL>                   $surface        A material used as a surface in some artwork, e.g. Canvas, Paper, Wood, Board, etc.
 * @property-read SchemaTypeList<QuantitativeValue|Distance> $depth          The depth of the item.
 */
interface VisualArtwork extends CreativeWork
{
}
