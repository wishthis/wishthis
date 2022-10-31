<?php

/**
 * Return the canonical URL
 *
 * @see https://github.com/oscarotero/Embed/issues/478
 */

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class Url extends Detector
{
    public function detect(): UriInterface
    {
        $oembed = $this->extractor->getOEmbed();

        return $oembed->url('url')
            ?: $oembed->url('web_page')
            ?: $this->getCanonical()
            ?: $this->extractor->getUri();
    }

    protected function getCanonical(): ?UriInterface
    {
        $document = $this->extractor->getDocument();

        foreach ($document->select('.//link[@canonical]')->nodes() as $node) {
            $href = $node->getAttribute('href');

            if ($href) {
                return $this->extractor->resolveUri($href);
            }
        }

        return null;
    }
}
