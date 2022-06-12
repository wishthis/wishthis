<?php

/**
 * Cache embed requests.
 *
 * @see https://github.com/oscarotero/Embed/issues/471
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis\Cache;

class Embed extends Cache
{
    /**
     * Private
     */
    protected function getFilepath(): string
    {
        return parent::getFilepath() . '.json';
    }

    /**
     * Public
     */
    public function __construct($url)
    {
        parent::__construct($url);

        $this->directory .= '/wishes';
    }

    public function get(bool $generateCache = false): \stdClass
    {
        $filepath = $this->getFilepath();

        $info = $this->exists() ? json_decode(file_get_contents($filepath)) : new \stdClass();

        if (true === $this->generateCache() || true === $generateCache) {
            /**
             * @link https://github.com/oscarotero/Embed
             */
            $embed = new \Embed\Embed();

            $info_simplified                = new \stdClass();
            $info_simplified->authorName    = '';
            $info_simplified->authorUrl     = '';
            $info_simplified->cms           = '';
            $info_simplified->code          = '';
            $info_simplified->description   = '';
            $info_simplified->favicon       = '';
            $info_simplified->feeds         = array();
            $info_simplified->icon          = '';
            $info_simplified->image         = null;
            $info_simplified->keywords      = array();
            $info_simplified->language      = '';
            $info_simplified->languages     = array();
            $info_simplified->license       = '';
            $info_simplified->providerName  = '';
            $info_simplified->providerUrl   = '';
            $info_simplified->publishedTime = '';
            $info_simplified->redirect      = '';
            $info_simplified->title         = $this->url;
            $info_simplified->url           = $this->url;

            if ($generateCache) {
                try {
                    $info                           = $embed->get($this->url);
                    $info_simplified->authorName    = (string) $info->authorName;
                    $info_simplified->authorUrl     = (string) $info->authorUrl;
                    $info_simplified->cms           = (string) $info->cms;
                    $info_simplified->code          = (string) $info->code;
                    $info_simplified->description   = (string) $info->description;
                    $info_simplified->favicon       = (string) $info->favicon;
                    $info_simplified->feeds         = (array)  $info->feeds;
                    $info_simplified->icon          = (string) $info->icon;
                    $info_simplified->image         = isset($info->image) && $info->image ? (string) $info->image : null;
                    $info_simplified->keywords      = (array)  $info->keywords;
                    $info_simplified->language      = (string) $info->language;
                    $info_simplified->languages     = (array)  $info->languages;
                    $info_simplified->license       = (string) $info->license;
                    $info_simplified->providerName  = (string) $info->providerName;
                    $info_simplified->providerUrl   = (string) $info->providerUrl;
                    $info_simplified->publishedTime = $info->publishedTime ? $info->publishedTime->format('d.m.Y') : '';
                    $info_simplified->redirect      = (string) $info->redirect;
                    $info_simplified->title         = (string) $info->title;
                    $info_simplified->url           = (string) $info->url;
                } catch (\Throwable $ex) {
                    $generateCache = false;

                    echo $ex->getMessage();
                }

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

                /** Favicon */
                if (str_contains(pathinfo($info_simplified->favicon, PATHINFO_EXTENSION), 'ico')) {
                    $ch = curl_init($info_simplified->favicon);
                    curl_setopt_array($ch, $ch_options);

                    $favicon = curl_exec($ch);
                    $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    curl_close($ch);

                    $info_simplified->favicon = $favicon && 200 === $code ? 'data:image/x-icon;base64,' . base64_encode($favicon) : '';
                }

                /** Response code */
                $ch = curl_init($info_simplified->url);
                curl_setopt_array($ch, $ch_options);

                $favicon = curl_exec($ch);
                $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (0 === $code || 404 === $code) {
                    $generateCache = false;
                }

                curl_close($ch);

                /** Update info */
                $info = $info_simplified;
            }

            if ($generateCache) {
                $this->write($info);
            }
        }

        return $info;
    }
}
