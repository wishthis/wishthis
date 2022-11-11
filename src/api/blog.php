<?php

/**
 * Blog
 *
 * @category API
 */

namespace wishthis;

global $page;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

$dateFormatter = new \IntlDateFormatter(
    $_SESSION['user']->getLocale(),
    \IntlDateFormatter::MEDIUM,
    \IntlDateFormatter::NONE
);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $posts = Blog::getPosts();
        $html  = '';

        for ($i = 0; $i < 2; $i++) {
            $post = $posts[$i];
            $date = $dateFormatter->format(strtotime($post->date));

            $html .= '<div class="item">';
            $html .= '    <i class="large rss middle aligned icon"></i>';
            $html .= '    <div class="content">';
            $html .= '         <a class="header" href="' . Page::PAGE_POST . '&slug=' . $post->slug . '">' . $post->title->rendered . '</a>';
            $html .= '         <div class="description">' . sprintf(__('Posted on %s'), $date) . '</div>';
            $html .= '    </div>';
            $html .= '</div>';
        }

        $response['posts'] = $posts;
        $response['html']  = $html;
        break;
}
