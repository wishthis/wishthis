<?php

/**
 * cache.php
 *
 * Cache embed requests.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class EmbedCache
{
    private string $directory = ROOT . '/src/cache';
    private string $noImage   = '/src/assets/img/no-image.svg';

    private string $identifier;
    private string $filepath;

    public function __construct(private string $url)
    {
        $this->identifier = md5($this->url);
        $this->filepath   = $this->directory . '/' . $this->identifier;
    }

    public function get(bool $generateCache = true): mixed
    {
        $info       = null;
        $maxAge     = 2592000; // 30 days
        $age        = file_exists($this->filepath) ? time() - filemtime($this->filepath) : $maxAge;

        if ($this->exists() && $age <= $maxAge) {
            $info = json_decode(file_get_contents($this->filepath));
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
            $info_simplified->image         = $this->noImage;
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
                    $info = $embed->get($this->url);

                    $info_simplified->authorName    = (string) $info->authorName;
                    $info_simplified->authorUrl     = (string) $info->authorUrl;
                    $info_simplified->cms           = (string) $info->cms;
                    $info_simplified->code          = (string) $info->code;
                    $info_simplified->description   = (string) $info->description;
                    $info_simplified->favicon       = (string) $info->favicon;
                    $info_simplified->feeds         = (array)  $info->feeds;
                    $info_simplified->icon          = (string) $info->icon;
                    $info_simplified->image         = (string) $info->image;
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

                    $info_simplified->title = $ex->getMessage();
                }
            }

            $info = $info_simplified;

            if ($generateCache) {
                file_put_contents($this->filepath, json_encode($info));
            }
        }

        return $info;
    }

    public function exists(): bool
    {
        return file_exists($this->filepath);
    }
}
