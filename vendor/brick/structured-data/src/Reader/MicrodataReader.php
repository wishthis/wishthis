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

/**
 * Reads Microdata embedded into a HTML document.
 *
 * https://www.w3.org/TR/microdata/
 *
 * @todo support for the itemref attribute
 */
class MicrodataReader implements Reader
{
    /**
     * @inheritDoc
     */
    public function read(DOMDocument $document, string $url) : array
    {
        $xpath = new DOMXPath($document);

        /**
         * An item is a top-level Microdata item if its element does not have an itemprop attribute.
         *
         * https://www.w3.org/TR/microdata/#associating-names-with-items
         */
        $nodes = $xpath->query('//*[@itemscope and not(@itemprop)]');
        $nodes = iterator_to_array($nodes);

        return array_map(function(DOMNode $node) use ($xpath, $url) {
            return $this->nodeToItem($node, $xpath, $url);
        }, $nodes);
    }

    /**
     * Extracts information from a DOMNode into an Item.
     *
     * @param DOMNode  $node  A DOMNode representing an element with the itemscope attribute.
     * @param DOMXPath $xpath A DOMXPath object created from the node's document element.
     * @param string   $url   The URL the document was retrieved from, for relative URL resolution.
     *
     * @return Item
     */
    private function nodeToItem(DOMNode $node, DOMXPath $xpath, string $url) : Item
    {
        $itemid = $node->attributes->getNamedItem('itemid');

        if ($itemid !== null) {
            /**
             * The global identifier of an item is the value of its element's itemid attribute, if it has one, resolved
             * relative to the element on which the attribute is specified. If the itemid attribute is missing or if
             * resolving it fails, it is said to have no global identifier.
             *
             * https://www.w3.org/TR/microdata/#items
             */
            $id = resolve($url, $itemid->textContent);
        } else {
            $id = null;
        }

        $itemtype = $node->attributes->getNamedItem('itemtype');

        if ($itemtype !== null) {
            /**
             * The item types of an item are the tokens obtained by splitting the element's itemtype attribute's value
             * on spaces.
             *
             * https://www.w3.org/TR/microdata/#items
             */
            $types = explode(' ', $itemtype->textContent);

            /**
             * If the itemtype attribute is missing or parsing it in this way finds no tokens, the item is said to have
             * no item types.
             */
            $types = array_values(array_filter($types, function(string $type) {
                return $type !== '';
            }));
        } else {
            $types = [];
        }

        $item = new Item($id, ...$types);

        // Find all nested properties
        $itemprops = $xpath->query('.//*[@itemprop]', $node);
        $itemprops = iterator_to_array($itemprops);

        // Exclude properties that are inside a nested item; XPath does not seem to provide a way to do this.
        // See: https://stackoverflow.com/q/26365495/759866
        $itemprops = array_filter($itemprops, function(DOMNode $itemprop) use ($node, $xpath) {
            for (;;) {
                $itemprop = $itemprop->parentNode;

                if ($itemprop->isSameNode($node)) {
                    return true;
                }

                if ($itemprop->attributes->getNamedItem('itemscope')) {
                    return false;
                }
            }

            // Unreachable, but makes static analysis happy
            return false;
        });

        $vocabularyIdentifier = $this->getVocabularyIdentifier($types);

        /** @var DOMNode[] $itemprops */
        foreach ($itemprops as $itemprop) {
            /**
             * An element introducing a property can introduce multiple properties at once, to avoid duplication when
             * some of the properties have the same value.
             *
             * https://www.w3.org/TR/microdata/#ex-multival
             */
            $names = $itemprop->attributes->getNamedItem('itemprop')->textContent;
            $names = explode(' ', $names);

            foreach ($names as $name) {
                /**
                 * Each token must be either a valid absolute URL or a a string that contains no "." (U+002E) characters
                 * and no ":" (U+003A) characters.
                 *
                 * https://www.w3.org/TR/microdata/#items
                 *
                 * We therefore consider anything containing these characters as an absolute URL, and only prepend the
                 * vocabulary identifier if none of these characters are found.
                 */
                if (strpos($name, '.') === false && strpos($name, ':') === false) {
                    $name = $vocabularyIdentifier . $name;
                }

                $value = $this->getPropertyValue($itemprop, $xpath, $url);

                $item->addProperty($name, $value);
            }
        }

        return $item;
    }

    /**
     * https://www.w3.org/TR/microdata/#values
     *
     * @param DOMNode  $node  A DOMNode representing an element with the itemprop attribute.
     * @param DOMXPath $xpath A DOMXPath object created from the node's document element.
     * @param string   $url   The URL the document was retrieved from, for relative URL resolution.
     *
     * @return Item|string
     */
    private function getPropertyValue(DOMNode $node, DOMXPath $xpath, string $url)
    {
        /**
         * If the element also has an itemscope attribute: the value is the item created by the element.
         */
        $attr = $node->attributes->getNamedItem('itemscope');

        if ($attr !== null) {
            return $this->nodeToItem($node, $xpath, $url);
        }

        /**
         * If the element has a content attribute: the value is the textContent of the element's content attribute.
         */
        $attr = $node->attributes->getNamedItem('content');

        if ($attr !== null) {
            return $attr->textContent;
        }

        /**
         * If the element is an audio, embed, iframe, img, source, track, or video element: if the element has a src
         * attribute, let proposed value be the result of resolving that attribute's textContent. If proposed value is a
         * valid absolute URL: The value is proposed value. Otherwise the value is the empty string.
         */
        $elements = ['audio', 'embed', 'iframe', 'img', 'source', 'track', 'video'];

        if (in_array($node->nodeName, $elements, true)) {
            $attr = $node->attributes->getNamedItem('src');

            if ($attr !== null) {
                try {
                    return resolve($url, $attr->textContent);
                } catch (InvalidUriException $e) {
                    return '';
                }
            }
        }

        /**
         * If the element is an a, area, or link element: if the element has an href attribute, let proposed value be
         * the result of resolving that attribute's textContent. If proposed value is a valid absolute URL: The value is
         * proposed value. Otherwise the value is the empty string.
         */
        $elements = ['a', 'area', 'link'];

        if (in_array($node->nodeName, $elements, true)) {
            $attr = $node->attributes->getNamedItem('href');

            if ($attr !== null) {
                try {
                    return resolve($url, $attr->textContent);
                } catch (InvalidUriException $e) {
                    return '';
                }
            }
        }

        /**
         * If the element is an object element: if the element has a data attribute, let proposed value be the result of
         * resolving that attribute's textContent. If proposed value is a valid absolute URL: The value is proposed
         * value. Otherwise the value is the empty string.
         */
        if ($node->nodeName === 'object') {
            $attr = $node->attributes->getNamedItem('data');

            if ($attr !== null) {
                try {
                    return resolve($url, $attr->textContent);
                } catch (InvalidUriException $e) {
                    return '';
                }
            }
        }

        /**
         * If the element is a data or meter element: if the element has a value attribute, the value is that
         * attribute's textContent.
         */
        if ($node->nodeName === 'data' || $node->nodeName === 'meter') {
            $attr = $node->attributes->getNamedItem('value');

            if ($attr !== null) {
                return $attr->textContent;
            }
        }

        /**
         * If the element is a time element: if the element has a datetime attribute, the value is that attribute's
         * textContent.
         */
        if ($node->nodeName === 'time') {
            $attr = $node->attributes->getNamedItem('datetime');

            if ($attr !== null) {
                return $attr->textContent;
            }
        }

        /**
         * Otherwise: the value is the element's textContent.
         *
         * Note that even though this is not suggested by the spec, we remove extra whitespace that's likely to be
         * an artifact of HTML formatting.
         */
        return trim(preg_replace('/\s+/', ' ', $node->textContent));
    }

    /**
     * Returns the vocabulary identifier for a given type.
     *
     * https://www.w3.org/TR/microdata/#dfn-vocabulary-identifier
     *
     * @param string[] $types The types, as valid absolute URLs.
     *
     * @return string
     */
    private function getVocabularyIdentifier(array $types) : string
    {
        if (! $types) {
            return '';
        }

        $type = $types[0];

        $pos = strpos($type, '#');

        if ($pos !== false) {
            return substr($type, 0, $pos + 1);
        }

        $pos = strrpos($type, '/');

        if ($pos !== false) {
            return substr($type, 0, $pos + 1);
        }

        return $type . '/';
    }
}
