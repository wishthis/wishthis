<?php

namespace wishthis;

class PageControllerApiBlog extends PageController
{
    protected string $id = 'api-blog';

    public function __construct()
    {
        $this->pageTitle = 'API';

        parent::__construct();
    }

    public function blog(): void
    {
        $posts = Blog::getPosts();
        $user  = User::getCurrent();

        $dateFormatter = new \IntlDateFormatter(
            $user->getLocale(),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::NONE
        );

        $html = '';

        \ob_start();

        for ($i = 0; $i < 2; $i++) {
            $post = $posts[$i];
            $date = $dateFormatter->format(\strtotime($post->date));

            $html .= '<div class="item">';
            $html .= '    <i class="large rss middle aligned icon"></i>';
            $html .= '    <div class="content">';
            $html .= '         <a class="header" href="' . Page::PAGE_POST . '/' . $post->slug . '">' . $post->title->rendered . '</a>';
            $html .= '         <div class="description">' . \sprintf(__('Posted on %s'), $date) . '</div>';
            $html .= '    </div>';
            $html .= '</div>';
        }

        $response['posts'] = $posts;
        $response['html']  = $html;

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }
}
