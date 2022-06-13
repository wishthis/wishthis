<?php

/**
 * Returns the pretty version of a URL.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class URL
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = urldecode($url);
    }

    public function isPretty(): bool
    {
        $isPretty = 1 === preg_match('/^\/[a-z0-9\/]+/', $this->url);

        return $isPretty;
    }

    public function getPermalink(): string
    {
        $htaccess  = preg_split('/\r\n|\r|\n/', file_get_contents(ROOT . '/.htaccess'));
        $permalink = '';

        foreach ($htaccess as $index => $line) {
            $parts = explode(chr(32), trim($line));

            if (count($parts) >= 2) {
                switch ($parts[0]) {
                    case 'RewriteRule':
                        $rewriteRule  = $parts[1];
                        $target       = $parts[2];

                        $regex = str_replace('/', '\/', $rewriteRule);

                        if (preg_match('/' . $regex . '/', ltrim($this->url, '/'), $matches)) {
                            $permalink = $target;

                            preg_match_all('/\$\d+/', $target, $placeholders);
                            $placeholders = reset($placeholders);

                            foreach ($placeholders as $index => $placeholder) {
                                $permalink = str_replace($placeholder, $matches[$index + 1], $permalink);
                            }
                        }
                        break;
                }
            }
        }

        return $permalink;
    }

    public function getPretty(): string
    {
        $htaccess   = preg_split('/\r\n|\r|\n/', file_get_contents(ROOT . '/.htaccess'));
        $pretty_url = '';

        if (!$this->url) {
            return '';
        }

        foreach ($htaccess as $index => $line) {
            $parts = explode(chr(32), trim($line));

            if (count($parts) >= 2) {
                switch ($parts[0]) {
                    case 'RewriteRule':
                        $rewriteRule = $parts[1];
                        $rewriteRule = ltrim($rewriteRule, '^');
                        $rewriteRule = rtrim($rewriteRule, '$');
                        $target      = $parts[2];
                        $keys        = array_map(
                            function ($item) {
                                return explode('=', $item)[0];
                            },
                            explode('&', parse_url($target, PHP_URL_QUERY))
                        );
                        $flags       = explode(',', substr($parts[3], 1, -1)) ?? array();
                        $parameters  = query_to_key_value_pair($this->url);

                        preg_match_all('/\(.+?\)/', $rewriteRule, $regexes);

                        $countMatches = 0;

                        foreach ($regexes as $matches) {
                            foreach ($matches as $match) {
                                foreach ($parameters as $key => $value) {
                                    if (
                                           preg_match('/^' . $match . '$/', $value)
                                        && in_array($key, $keys)
                                        && count($parameters) === count($keys)
                                    ) {
                                        $rewriteRule = str_replace($match, $value, $rewriteRule);

                                        $countMatches++;
                                        break;
                                    }
                                }
                            }

                            if ($countMatches > 0 && $countMatches === count($matches)) {
                                $pretty_url = '/' . $rewriteRule;

                                if (in_array('L', $flags)) {
                                    break 3;
                                }
                            }
                        }
                        break;
                }
            }
        }

        return $pretty_url ?: '/?' . $this->url;
    }
}
