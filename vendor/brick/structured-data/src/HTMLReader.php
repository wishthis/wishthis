<?php

declare(strict_types=1);

namespace Brick\StructuredData;

class HTMLReader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * HTMLReader constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Reads the items contained in the given HTML string.
     *
     * @param string $html The HTML string to read.
     * @param string $url  The URL the document was retrieved from. This will be used only to resolve relative URLs in
     *                     property values. No attempt will be performed to connect to this URL.
     *
     * @return Item[] The top-level items.
     */
    public function read(string $html, string $url) : array
    {
        $document = DOMBuilder::fromHTML($html);

        return $this->reader->read($document, $url);
    }

    /**
     * Reads the items contained in the given HTML file.
     *
     * @param string $file The HTML file to read.
     * @param string $url  The URL the document was retrieved from. This will be used only to resolve relative URLs in
     *                     property values. No attempt will be performed to connect to this URL.
     *
     * @return Item[] The top-level items.
     */
    public function readFile(string $file, string $url) : array
    {
        $document = DOMBuilder::fromHTMLFile($file);

        return $this->reader->read($document, $url);
    }
}
