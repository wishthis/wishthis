<?php

declare(strict_types=1);

namespace Brick\StructuredData;

use DOMDocument;

class DOMBuilder
{
    /**
     * Builds a DOMDocument from an HTML string.
     *
     * @param string $html
     *
     * @return DOMDocument
     */
    public static function fromHTML(string $html) : DOMDocument
    {
        $document = new DOMDocument();
        $document->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR);

        return $document;
    }

    /**
     * Builds a DOMDocument from an HTML file.
     *
     * @param string $file
     *
     * @return DOMDocument
     */
    public static function fromHTMLFile(string $file) : DOMDocument
    {
        $document = new DOMDocument();
        $document->loadHTMLFile($file, LIBXML_NOWARNING | LIBXML_NOERROR);

        return $document;
    }
}
