<?php
declare(strict_types = 1);

namespace HtmlParser;

use Exception;
use DOMNode;
use DOMDocument;
use DOMDocumentFragment;
use SimpleXMLElement;
use DOMXPath;

class Parser
{
    public static function stringify(DOMNode $node): string
    {
        if ($node instanceof DOMDocument) {
            return $node->saveHTML($node);
        }

        return $node->ownerDocument->saveHTML($node);
    }

    public static function parse(string $html, ?string $encoding = null): DOMDocument
    {
        $detected = $encoding ?? mb_detect_encoding($html);
        
        if ($detected) {
            $html = mb_encode_numericentity($html, [0x80, 0xFFFFFF, 0, -1], $detected);
        }

        $document = self::createDOMDocument($html);
        $xpath = new DOMXPath($document);

        $charset = $xpath->query('.//meta[@charset]')->item(0);
        $httpEquiv = $xpath->query('.//meta[@http-equiv]')->item(0);

        if ($charset || $httpEquiv) {
            $charset = $charset ? self::stringify($charset) : null;
            $httpEquiv = $httpEquiv ? self::stringify($httpEquiv) : null;

            $html = preg_replace(
                '/<head[^>]*>/',
                '<head>'.$charset.$httpEquiv,
                $html
            );

            return self::createDOMDocument($html);
        }

        return $document;
    }

    public static function parseFragment(string $html, ?string $encoding = null): DOMDocumentFragment
    {
        $html = "<html><head></head><body>{$html}</body></html>";
        $document = static::parse($html, $encoding);
        $fragment = $document->createDocumentFragment();

        $body = $document->getElementsByTagName('body')->item(0);

        $nodes = [];

        foreach ($body->childNodes as $node) {
            $nodes[] = $node;
        }

        foreach ($nodes as $node) {
            $fragment->appendChild($node);
        }

        return $fragment;
    }

    private static function createDOMDocument(string $code): DOMDocument
    {
        $errors = libxml_use_internal_errors(true);
        
        // Enabled by default in PHP 8
        if (PHP_MAJOR_VERSION < 8) {
            $entities = libxml_disable_entity_loader(true);
        }

        $document = new DOMDocument();
        $document->loadHTML($code);

        libxml_use_internal_errors($errors);

        if (PHP_MAJOR_VERSION < 8) {
            libxml_disable_entity_loader($entities);
        }

        return $document;
    }
}
