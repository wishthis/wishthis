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

        $this->directory .= '/embed';
    }

    public function get(bool $generateCache = false): \stdClass
    {
        $filepath = $this->getFilepath();

        /** Get existing info */
        $info = $this->exists() ? json_decode(file_get_contents($filepath)) : new \stdClass();

        if (($this->exists() && $this->getAge() > $this->maxAge) || true === $generateCache) {
            $infoToSave = $info;

            try {
                /**
                 * Fetch embed info
                 *
                 * @link https://github.com/oscarotero/Embed
                 */
                $embed = new \Embed\Embed();
                $info  = $embed->get($this->url);

                /** Convert embed info to a saveable format (JSON) stdClass */
                $infoToSave                = new \stdClass();
                $infoToSave->authorName    = (string) $info->authorName;
                $infoToSave->authorUrl     = (string) $info->authorUrl;
                $infoToSave->cms           = (string) $info->cms;
                $infoToSave->code          = (string) $info->code;
                $infoToSave->description   = (string) $info->description;
                $infoToSave->favicon       = (string) $info->favicon;
                $infoToSave->feeds         = (array)  $info->feeds;
                $infoToSave->icon          = (string) $info->icon;
                $infoToSave->image         = (string) $info->image;
                $infoToSave->keywords      = (array)  $info->keywords;
                $infoToSave->language      = (string) $info->language;
                $infoToSave->languages     = (array)  $info->languages;
                $infoToSave->license       = (string) $info->license;
                $infoToSave->providerName  = (string) $info->providerName;
                $infoToSave->providerUrl   = (string) $info->providerUrl;
                $infoToSave->publishedTime = $info->publishedTime ? $info->publishedTime->format('d.m.Y') : '';
                $infoToSave->redirect      = (string) $info->redirect;
                $infoToSave->title         = (string) $info->title;
                $infoToSave->url           = (string) $info->url;

                $info = $infoToSave;
            } catch (\Throwable $ex) {
                $generateCache = false;

                echo $ex->getMessage();
            }

            if ($generateCache) {
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
                if (str_contains(pathinfo($info->favicon, PATHINFO_EXTENSION), 'ico')) {
                    $ch = curl_init($info->favicon);
                    curl_setopt_array($ch, $ch_options);

                    $favicon = curl_exec($ch);
                    $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    curl_close($ch);

                    $info->favicon = $favicon && ($code >= 200 && $code < 400) ? 'data:image/x-icon;base64,' . base64_encode($favicon) : '';
                }

                /** URL */
                $codeURL = \wishthis\URL::getResponseCode($info->url);

                if ($codeURL < 200 || $codeURL >= 400) {
                    $generateCache = false;
                }
            }

            if ($generateCache) {
                $this->write($info);
            }
        }

        return $info;
    }
}
