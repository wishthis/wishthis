<?php

/**
 * Returns the pretty version of a URL.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class URL
{
    public function __construct(private string $url)
    {
    }

    public function getPretty(): string
    {
        $htaccess    = explode(PHP_EOL, file_get_contents(ROOT . '/.htaccess'));
        $pretty_url = '';

        foreach ($htaccess as $index => $line) {
            $parts = explode(chr(32), $line);

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

                        $parameters_pairs = explode('&', parse_url($this->url, PHP_URL_PATH));
                        $parameters       = array();

                        foreach ($parameters_pairs as $index => $pair) {
                            $parts = explode('=', $pair);
                            $key   = reset($parts);
                            $value = end($parts);

                            $parameters[$key] = $value;
                        }

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

        return $pretty_url;
    }
}
