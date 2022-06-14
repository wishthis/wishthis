<?php

/**
 * Blog
 *
 * @category API
 */

namespace wishthis;

ob_start();

$api = true;

require '../../index.php';

$response      = array();
$dateFormatter = new \IntlDateFormatter(
    $user->locale,
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

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
