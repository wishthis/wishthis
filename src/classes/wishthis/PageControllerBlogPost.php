<?php

namespace wishthis;

class PageControllerBlogPost extends PageController
{
    protected string $id = 'blog-post';

    private string $postSlug;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Post');
        $this->postSlug  = $parameters['slug'];

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $postSlug      = $this->postSlug;
        $posts         = Blog::getPreviousCurrentNextPostBySlug($postSlug);
        $post          = $posts['current'];
        $postTitle     = $post->title->rendered;
        $postHtml      = $post->content->rendered;
        $postMediaHtml = isset($post->featured_media) && 0 !== $post->featured_media
                       ? Blog::getMediaHTML($post->featured_media)
                       : '';
        $postMedia     = isset($post->featured_media) && 0 !== $post->featured_media
                       ? Blog::getMedia($post->featured_media)
                       : new \stdClass();

        $this->placeholders['POST_MEDIA']        = $postMediaHtml;
        $this->placeholders['POST_TITLE']        = $postTitle;
        $this->placeholders['POST_HTML']         = $postHtml;
        $this->placeholders['LINK_TOP_HEADING']  = __('Top');
        $this->placeholders['LINK_BLOG_HEADING'] = __('Blog');
        $this->placeholders['LINK_BLOG_URL']     = Page::PAGE_BLOG;

        parent::setPlaceholders();
    }

    public function default(): void
    {
        parent::render();
    }
}
