<?php

/**
 * The wishthis blog.
 */

namespace wishthis;

class Blog
{
    private const URL            = 'https://wishthis.online/src/blog';
    private const ENDPOINT_POSTS = self::URL . '/wp-json/wp/v2/posts';

    public static function getPosts(): array {
        $posts_remote = file_get_contents(self::ENDPOINT_POSTS);
        $posts        = array();

        if (false !== $posts_remote) {
            $posts = json_decode($posts_remote);
        }

        return $posts;
    }
}
