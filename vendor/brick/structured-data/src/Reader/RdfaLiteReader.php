<?php

declare(strict_types=1);

namespace Brick\StructuredData\Reader;

use Brick\StructuredData\Item;
use Brick\StructuredData\Reader;

use DOMDocument;
use DOMNode;
use DOMXPath;

use Sabre\Uri\InvalidUriException;
use function Sabre\Uri\resolve;
use function Sabre\Uri\parse;
use function Sabre\Uri\build;

/**
 * Reads RDFa Lite embedded into a HTML document.
 *
 * https://www.w3.org/TR/rdfa-lite/
 *
 * @todo support for the prefix attribute; only predefined prefixes are supported right now
 */
class RdfaLiteReader implements Reader
{
    /**
     * The predefined RDFa prefixes.
     *
     * https://www.w3.org/2011/rdfa-context/rdfa-1.1
     */
    private const PREDEFINED_PREFIXES = [
        'as'      => 'https://www.w3.org/ns/activitystreams#',
        'csvw'    => 'http://www.w3.org/ns/csvw#',
        'cat'     => 'http://www.w3.org/ns/dcat#',
        'cc'      => 'http://creativecommons.org/ns#',
        'cnt'     => 'http://www.w3.org/2008/content#',
        'ctag'    => 'http://commontag.org/ns#',
        'dc'      => 'http://purl.org/dc/terms/',
        'dc11'    => 'http://purl.org/dc/elements/1.1/',
        'dcat'    => 'http://www.w3.org/ns/dcat#',
        'dcterms' => 'http://purl.org/dc/terms/',
        'dqv'     => 'http://www.w3.org/ns/dqv#',
        'duv'     => 'https://www.w3.org/TR/vocab-duv#',
        'earl'    => 'http://www.w3.org/ns/earl#',
        'foaf'    => 'http://xmlns.com/foaf/0.1/',
        'gldp'    => 'http://www.w3.org/ns/people#',
        'gr'      => 'http://purl.org/goodrelations/v1#',
        'grddl'   => 'http://www.w3.org/2003/g/data-view#',
        'ht'      => 'http://www.w3.org/2006/http#',
        'ical'    => 'http://www.w3.org/2002/12/cal/icaltzd#',
        'ldp'     => 'http://www.w3.org/ns/ldp#',
        'ma'      => 'http://www.w3.org/ns/ma-ont#',
        'oa'      => 'http://www.w3.org/ns/oa#',
        'odrl'    => 'http://www.w3.org/ns/odrl/2/',
        'og'      => 'http://ogp.me/ns#',
        'org'     => 'http://www.w3.org/ns/org#',
        'owl'     => 'http://www.w3.org/2002/07/owl#',
        'prov'    => 'http://www.w3.org/ns/prov#',
        'ptr'     => 'http://www.w3.org/2009/pointers#',
        'qb'      => 'http://purl.org/linked-data/cube#',
        'rev'     => 'http://purl.org/stuff/rev#',
        'rdf'     => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
        'rdfa'    => 'http://www.w3.org/ns/rdfa#',
        'rdfs'    => 'http://www.w3.org/2000/01/rdf-schema#',
        'rif'     => 'http://www.w3.org/2007/rif#',
        'rr'      => 'http://www.w3.org/ns/r2rml#',
        'schema'  => 'http://schema.org/',
        'sd'      => 'http://www.w3.org/ns/sparql-service-description#',
        'sioc'    => 'http://rdfs.org/sioc/ns#',
        'skos'    => 'http://www.w3.org/2004/02/skos/core#',
        'skosxl'  => 'http://www.w3.org/2008/05/skos-xl#',
        'ssn'     => 'http://www.w3.org/ns/ssn/',
        'sosa'    => 'http://www.w3.org/ns/sosa/',
        'time'    => 'http://www.w3.org/2006/time#',
        'v'       => 'http://rdf.data-vocabulary.org/#',
        'vcard'   => 'http://www.w3.org/2006/vcard/ns#',
        'void'    => 'http://rdfs.org/ns/void#',
        'wdr'     => 'http://www.w3.org/2007/05/powder#',
        'wdrs'    => 'http://www.w3.org/2007/05/powder-s#',
        'xhv'     => 'http://www.w3.org/1999/xhtml/vocab#',
        'xml'     => 'http://www.w3.org/XML/1998/namespace',
        'xsd'     => 'http://www.w3.org/2001/XMLSchema#',
    ];

    /**
     * @inheritDoc
     */
    public function read(DOMDocument $document, string $url) : array
    {
        $xpath = new DOMXPath($document);

        /**
         * Top-level item have a typeof attribute and no property attribute.
         */
        $nodes = $xpath->query('//*[@typeof and not(@property)]');
        $nodes = iterator_to_array($nodes);

        return array_map(function(DOMNode $node) use ($xpath, $url) {
            return $this->nodeToItem($node, $xpath, $url, self::PREDEFINED_PREFIXES, null);
        }, $nodes);
    }

    /**
     * Extracts information from a DOMNode into an Item.
     *
     * @param DOMNode     $node       A DOMNode representing an element with the typeof attribute.
     * @param DOMXPath    $xpath      A DOMXPath object created from the node's document element.
     * @param string      $url        The URL the document was retrieved from, for relative URL resolution.
     * @param string[]    $prefixes   The prefixes in use, as a map of prefix to vocabulary URL.
     * @param string|null $vocabulary The URL of the vocabulary in use, if any.
     *                                This is the content of the vocab attribute of the closest item ancestor.
     *
     * @return Item
     */
    private function nodeToItem(DOMNode $node, DOMXPath $xpath, string $url, array $prefixes, ?string $vocabulary) : Item
    {
        $vocabulary = $this->updateVocabulary($node, $vocabulary);

        /**
         * The resource attribute holds the item identifier, than must be resolved relative to the current URL.
         *
         * https://www.w3.org/TR/rdfa-lite/#resource
         */
        $resource = $node->attributes->getNamedItem('resource');

        if ($resource !== null) {
            $id = resolve($url, $resource->textContent);
        } else {
            $id = null;
        }

        $typeof = $node->attributes->getNamedItem('typeof');

        // Multiple types can be specified, separated with spaces
        $types = explode(' ', $typeof->textContent);

        // Resolve types, replace invalid ones with empty strings; we'll filter them out in the next step
        $types = array_map(function(string $type) use ($prefixes, $vocabulary) {
            if ($type !== '') {
                $type = $this->resolveTerm($type, $prefixes, $vocabulary);

                if ($type !== null) {
                    return $type;
                }
            }

            return '';
        }, $types);

        // Remove empty values
        $types = array_values(array_filter($types, function(string $type) {
            return $type !== '';
        }));

        $item = new Item($id, ...$types);

        // Find all nested properties
        $properties = $xpath->query('.//*[@property]', $node);
        $properties = iterator_to_array($properties);

        // Exclude properties that are inside a nested item; XPath does not seem to provide a way to do this.
        // See: https://stackoverflow.com/q/26365495/759866
        $properties = array_filter($properties, function(DOMNode $itemprop) use ($node, $xpath) {
            for (;;) {
                $itemprop = $itemprop->parentNode;

                if ($itemprop->isSameNode($node)) {
                    return true;
                }

                if ($itemprop->attributes->getNamedItem('typeof')) {
                    return false;
                }
            }

            // Unreachable, but makes static analysis happy
            return false;
        });

        /** @var DOMNode[] $properties */
        foreach ($properties as $property) {
            $names = $property->attributes->getNamedItem('property')->textContent;

            // Multiple property names can be specified, separated with spaces
            $names = explode(' ', $names);

            foreach ($names as $name) {
                $name = $this->resolveTerm($name, $prefixes, $this->updateVocabulary($property, $vocabulary));

                if ($name === null) {
                    continue;
                }

                $value = $this->getPropertyValue($property, $xpath, $url, $prefixes, $vocabulary);

                $item->addProperty($name, $value);
            }
        }

        return $item;
    }

    /**
     * Returns whether the given URL is a valid absolute URL.
     *
     * @param string      $term       The term to resolve, e.g. 'name', 'schema:name', or 'http://schema.org/name'.
     * @param string[]    $prefixes   The prefixes in use, as a map of prefix to vocabulary URL.
     * @param string|null $vocabulary The current vocabulary URL, if any.
     *
     * @return string|null An absolute URL, or null if the term cannot be resolved.
     */
    private function resolveTerm(string $term, array $prefixes, ?string $vocabulary) : ?string
    {
        if ($this->isValidAbsoluteURL($term)) {
            return $term;
        }

        $parts = explode(':', $term);

        if (count($parts) === 2) {
            [$prefix, $term] = $parts;

            if (! isset($prefixes[$prefix])) {
                return null;
            }

            return $prefixes[$prefix] . $term;
        }

        if ($vocabulary === null) {
            return null;
        }

        return $vocabulary . $term;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    private function isValidAbsoluteURL(string $url) : bool
    {
        try {
            $parts = parse($url);
        } catch (InvalidUriException $e) {
            return false;
        }

        if ($parts['scheme'] === null) {
            return false;
        }

        if ($parts['host'] === null) {
            return false;
        }

        return true;
    }

    /**
     * Replaces the current vocabulary with the one from the vocab attribute of the current node, if set.
     *
     * @param DOMNode     $node       The DOMNode that may contain a vocab attribute.
     * @param string|null $vocabulary The URL of the vocabulary in use, if any.
     *
     * @return string|null The updated vocabulary URL, if any.
     */
    private function updateVocabulary(DOMNode $node, ?string $vocabulary) : ?string
    {
        $vocab = $node->attributes->getNamedItem('vocab');

        if ($vocab !== null) {
            return $this->checkVocabularyUrl($vocab->textContent);
        }

        return $vocabulary;
    }

    /**
     * Ensures that the vocabulary URL is a valid absolute URL, and ensure that it has a path.
     *
     * Example: http://schema.org would return http://schema.org/
     *
     * @param string $url
     *
     * @return string|null An absolute URL, or null if the input is not valid.
     */
    private function checkVocabularyUrl(string $url) : ?string
    {
        try {
            $parts = parse($url);
        } catch (InvalidUriException $e) {
            return null;
        }

        if ($parts['scheme'] === null) {
            return null;
        }

        if ($parts['host'] === null) {
            return null;
        }

        if ($parts['path'] === null) {
            $parts['path'] = '/';
        }

        return build($parts);
    }

    /**
     * https://www.w3.org/TR/microdata/#values
     *
     * @param DOMNode     $node       A DOMNode representing an element with the property attribute.
     * @param DOMXPath    $xpath      A DOMXPath object created from the node's document element.
     * @param string      $url        The URL the document was retrieved from, for relative URL resolution.
     * @param string[]    $prefixes   The prefixes in use, as a map of prefix to vocabulary URL.
     * @param string|null $vocabulary The URL of the vocabulary in use, if any.
     *
     * @return Item|string
     */
    private function getPropertyValue(DOMNode $node, DOMXPath $xpath, string $url, array $prefixes, ?string $vocabulary)
    {
        // If the element also has an typeof attribute, create an item from the element
        $attr = $node->attributes->getNamedItem('typeof');

        if ($attr !== null) {
            return $this->nodeToItem($node, $xpath, $url, $prefixes, $vocabulary);
        }

        // Look for a content attribute
        $attr = $node->attributes->getNamedItem('content');

        if ($attr !== null) {
            return $attr->textContent;
        }

        // Look for an href attribute
        $attr = $node->attributes->getNamedItem('href');

        if ($attr !== null) {
            try {
                return resolve($url, $attr->textContent);
            } catch (InvalidUriException $e) {
                return '';
            }
        }

        // Look for a src attribute
        $attr = $node->attributes->getNamedItem('src');

        if ($attr !== null) {
            try {
                return resolve($url, $attr->textContent);
            } catch (InvalidUriException $e) {
                return '';
            }
        }

        // Otherwise, take the value of the element's textContent. Note that even though this is not suggested by the
        // spec, we remove extra whitespace that's likely to be an artifact of HTML formatting.
        return trim(preg_replace('/\s+/', ' ', $node->textContent));
    }
}
