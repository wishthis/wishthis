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
        $ch_options = array(
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER         => false,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:99.0) Gecko/20100101 Firefox/99.0',
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $ch_options);
        curl_exec($ch);

        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (0 === $responseCode) {
            echo curl_error($ch);
        }

        curl_close($ch);

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
        $this->url = urldecode($url);

        $_GET = $this->getGET();
    }

    /**
     * Returns whether the current URL is pretty.
     *
     * @return boolean
     */
    public function isPretty(): bool
    {
        if ('/?' === substr($this->url, 0, 2)) {
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
        $htaccess  = preg_split('/\r\n|\r|\n/', file_get_contents(ROOT . '/.htaccess'));
        $permalink = '';

        foreach ($htaccess as $index => $line) {
            $parts = explode(chr(32), trim($line));

            if (count($parts) >= 2) {
                switch ($parts[0]) {
                    case 'RewriteRule':
                        $rewriteRule = $parts[1];
                        $target      = $parts[2];

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

    /**
     * Returns a pretty version of the current URL.
     *
     * @return string
     */
    public function getPretty(): string
    {
        $htaccess = preg_split('/\r\n|\r|\n/', file_get_contents(ROOT . '/.htaccess'));

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

                        parse_str(ltrim($target, '/?'), $parameters);
                        /** */

                        /** Determine a potential URL. */
                        $potential_url = $rewriteRule;

                        preg_match_all('/\(.+?\)/', $rewriteRule, $groups);
                        $groups = $groups[0];

                        for ($i = 0; $i < count($groups); $i++) {
                            foreach ($parameters as $key => $value) {
                                $replacement = '$' . $i + 1;

                                if ($replacement === $value && isset($_GET[$key])) {
                                    $potential_url = str_replace(
                                        $groups[$i],
                                        $_GET[$key],
                                        $potential_url
                                    );
                                }
                            }
                        }

                        $match = preg_match(
                            '/^' . str_replace(array('/'), array('\/'), $rewriteRule) . '$/',
                            $potential_url
                        );

                        if (1 === $match && count($_GET) === count(explode('/', $rewriteRule))) {
                            return '/' . $potential_url;
                        }
                        break;
                }
            }
        }

        if ('/?' === substr($this->url, 0, 2)) {
            return $this->url;
        }

        return '/?' . $this->url;
    }

    /**
     * Returns the current URL parameters, even for pretty URLs.
     *
     * @return array
     */
    public function getGET(): array
    {
        $queryString = $this->url;
        $GET         = array();

        if ($this->isPretty()) {
            $queryString = parse_url($this->getPermalink(), PHP_URL_QUERY);
        }

        if (null === $queryString) {
            return array();
        }

        if ('/?' === substr($queryString, 0, 2)) {
            $queryString = substr($queryString, 2);
        }

        parse_str($queryString, $GET);

        return $GET;
    }
}
