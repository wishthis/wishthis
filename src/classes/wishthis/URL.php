<?php

/**
 * Returns the pretty version of a URL.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class URL
{
    /**
     * Returns the HTTP status code of a URL.
     *
     * @param string $url
     *
     * @return integer
     */
    public static function getResponseCode(string $url): int
    {
        $ch_options = [
            \CURLOPT_AUTOREFERER    => true,
            \CURLOPT_CONNECTTIMEOUT => 30,
            \CURLOPT_FOLLOWLOCATION => true,
            \CURLOPT_HEADER         => false,
            \CURLOPT_MAXREDIRS      => 10,
            \CURLOPT_RETURNTRANSFER => true,
            \CURLOPT_SSL_VERIFYPEER => false,
            \CURLOPT_TIMEOUT        => 30,
            \CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:99.0) Gecko/20100101 Firefox/99.0',
        ];

        $ch = \curl_init($url);
        \curl_setopt_array($ch, $ch_options);
        \curl_exec($ch);

        $responseCode = \curl_getinfo($ch, \CURLINFO_HTTP_CODE);

        if (0 === $responseCode) {
            echo \curl_error($ch);
        }

        \curl_close($ch);

        return $responseCode;
    }

    /**
     * The current URL. Can be pretty or not.
     *
     * @var string
     */
    public string $url;

    /**
     * Constructor
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = \urldecode($url);
        $this->url = \str_replace('index.php', '', $this->url);

        while (\str_contains($this->url, '//')) {
            $this->url = \str_replace('//', '/', $this->url);
        }

        if (1 === \preg_match('/(http|https):\//', $this->url, $matches)) {
            $match    = $matches[0] ?? '';
            $protocol = $matches[1] ?? $_SERVER['REQUEST_SCHEME'] ?? 'http';

            $this->url = \str_replace($match, $protocol . '://', $this->url);
        }

        $_GET = $this->getGET();
    }

    /**
     * Returns whether the current URL is pretty.
     *
     * @return boolean
     */
    public function isPretty(): bool
    {
        if ('/?' === \substr($this->url, 0, 2)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the original, un-pretty URL or an empty string on failure.
     *
     * @return string
     */
    public function getPermalink(): string
    {
        $htaccess  = \preg_split('/\r\n|\r|\n/', \file_get_contents(ROOT . '/.htaccess'));
        $permalink = $this->url;

        foreach ($htaccess as $index => $line) {
            $parts = \explode(chr(32), trim($line));

            if (\count($parts) >= 2) {
                switch ($parts[0]) {
                    case 'RewriteRule':
                        $rewriteRule = $parts[1];
                        $target      = $parts[2];

                        $regex = \str_replace('/', '\/', $rewriteRule);

                        if (\preg_match('/' . $regex . '/', \ltrim($this->url, '/'), $matches)) {
                            $permalink = $target;

                            \preg_match_all('/\$\d+/', $target, $placeholders);
                            $placeholders = \reset($placeholders);

                            foreach ($placeholders as $index => $placeholder) {
                                $permalink = \str_replace($placeholder, $matches[$index + 1], $permalink);
                            }
                        }
                        break;
                }
            }
        }

        return $permalink;
    }

    /**
     * Returns a pretty version of the current URL.
     *
     * @return string
     */
    public function getPretty(): string
    {
        return $this->url;
    }

    /**
     * Returns the current URL parameters, even for pretty URLs.
     *
     * @return array
     */
    public function getGET(): array
    {
        $queryString = $this->url;
        $GET         = [];

        if ($this->isPretty()) {
            $queryString = \parse_url($this->getPermalink(), \PHP_URL_QUERY);
        }

        if (null === $queryString) {
            return [];
        }

        if ('/?' === \substr($queryString, 0, 2)) {
            $queryString = \substr($queryString, 2);
        }

        \parse_str($queryString, $GET);

        return $GET;
    }
}
