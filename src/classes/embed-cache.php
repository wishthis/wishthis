<?php

/**
 * Cache embed requests.
 *
 * @see https://github.com/oscarotero/Embed/issues/471
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class EmbedCache
{
    /**
     * Private
     */
    private string $directory = ROOT . '/src/cache';
    private string $filepath;

    private function getIdentifier(): string
    {
        return md5($this->url);
    }

    private function getFilepath(): string
    {
        return $this->directory . '/' . $this->getIdentifier();
    }

    /**
     * Public
     */
    public function __construct(private string $url)
    {
    }

    public function get(bool $generateCache = false): \stdClass
    {
        $info = null;

        if (!$this->generateCache() && false === $generateCache) {
            $info = json_decode(file_get_contents($this->getFilepath()));
        } else {
            /**
             * @link https://github.com/oscarotero/Embed
             */
            $embed = new \Embed\Embed();

            $info_simplified = new \stdClass();
            $info_simplified->authorName    = '';
            $info_simplified->authorUrl     = '';
            $info_simplified->cms           = '';
            $info_simplified->code          = '';
            $info_simplified->description   = '';
            $info_simplified->favicon       = '';
            $info_simplified->feeds         = array();
            $info_simplified->icon          = '';
            $info_simplified->image         = '';
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
                $info = $embed->get($this->url);

                $info_simplified->authorName    = (string) $info->authorName;
                $info_simplified->authorUrl     = (string) $info->authorUrl;
                $info_simplified->cms           = (string) $info->cms;
                $info_simplified->code          = (string) $info->code;
                $info_simplified->description   = (string) $info->description;
                $info_simplified->favicon       = (string) $info->favicon;
                $info_simplified->feeds         = (array)  $info->feeds;
                $info_simplified->icon          = (string) $info->icon;
                $info_simplified->image         = isset($info->image) ? (string) $info->image : null;
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

                try {
                } catch (\Throwable $ex) {
                    $generateCache = false;

                    $info_simplified->description = $ex->getMessage();
                }

                if (str_contains(pathinfo($info_simplified->favicon, PATHINFO_EXTENSION), 'ico')) {
                    $options = array(
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

                    $ch = curl_init($info_simplified->favicon);
                    curl_setopt_array($ch, $options);

                    $favicon = curl_exec($ch);
                    $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    curl_close($ch);

                    $info_simplified->favicon = $favicon && 200 === $code ? 'data:image/x-icon;base64,' . base64_encode($favicon) : '';
                }
            }

            $info = $info_simplified;

            if ($generateCache) {
                file_put_contents($this->getFilepath(), json_encode($info));
            }
        }

        return $info;
    }

    public function exists(): bool
    {
        return file_exists($this->getFilepath());
    }

    public function age(): int
    {
        return time() - filemtime($this->getFilepath());
    }

    public function maxAge(): int
    {
        return 2592000; // 30 days
    }

    public function generateCache(): bool
    {
        return !$this->exists()
            || ($this->exists() && $this->age() > $this->maxAge());
    }
}
