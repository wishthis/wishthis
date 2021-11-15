<?php

declare(strict_types=1);

namespace Brick\StructuredData;

use DOMDocument;

/**
 * Common interface for readers of each format: Microdata, RDFa Lite, JSON-LD.
 */
interface Reader
{
    /**
     * Reads the items contained in the given document.
     *
     * @param DOMDocument $document The DOM document to read.
     * @param string      $url      The URL the document was retrieved from. This will be used only to resolve relative
     *                              URLs in property values. The implementation must not attempt to connect to this URL.
     *
     * @return Item[] The top-level items.
     */
    public function read(DOMDocument $document, string $url) : array;
}
