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
    private $directory = ROOT . '/src/cache';

    public function __construct()
    {
    }

    public function get(string $url): mixed
    {
        $info       = null;
        $identifier = md5($url);
        $filepath   = $this->directory . '/' . $identifier;

        if (file_exists($filepath)) {
            $info = json_decode(file_get_contents($filepath));
        } else {
            /**
             * @link https://github.com/oscarotero/Embed
             */
            $embed = new \Embed\Embed();
            $info  = $embed->get($url);

            $info_simplified = new \stdClass();
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

            $info = $info_simplified;

            file_put_contents($filepath, json_encode($info));
        }

        return $info;
    }
}
