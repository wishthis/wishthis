<?php

namespace wishthis;

class PageControllerBlog extends PageController
{
    protected string $id                   = 'blog';
    protected bool $requiresAuthentication = false;

    public function __construct()
    {
        $this->pageTitle = __('Blog');

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $this->setPlaceholderBlogPosts();

        parent::setPlaceholders();
    }

    private function setPlaceholderBlogPosts(): void
    {
        $user  = User::getCurrent();
        $posts = Blog::getPosts();

        $dateFormatter = new \IntlDateFormatter(
            $user->getLocale(),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::NONE
        );

        \ob_start();
        ?>
        <div class="ui two column doubling stackable grid">
            <?php foreach ($posts as $post) { ?>
                <?php
                    $mediaHTML      = isset($post->featured_media) && (0 !== $post->featured_media)
                                    ? Blog::getMediaHTML($post->featured_media)
                                    : '';
                    $categoriesHTML = Blog::getCategoriesHTML($post->categories);
                    $postLink       = Page::PAGE_POST . '/' . $post->slug;
                ?>

                <div class="column">
                    <div class="ui fluid card stretch">
                        <div class="image">
                            <a href="<?= $postLink ?>"><?= $mediaHTML ?></a>
                        </div>
                        <div class="content">
                            <div class="header"><?= $post->title->rendered ?></div>
                            <div class="meta">
                                <a><?= $categoriesHTML ?></a>
                            </div>
                            <div class="description">
                                <?= $post->excerpt->rendered ?>
                                <p>
                                    <a href="<?= $postLink ?>"><?= __('Read more') ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="extra content">
                            <span class="right floated">
                                <?= $dateFormatter->format(\strtotime($post->date)) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if (\count($posts) > 4) { ?>
            <div class="ui hidden divider"></div>

            <div class="ui one column centered grid">
                <div class="column centered row">
                    <a href="#top" class="ui vertical animated button">
                        <div class="visible content">
                            <i class="arrow up icon"></i>
                        </div>
                        <div class="hidden content">
                            <?= __('Top') ?>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php

        $this->placeholders['BLOG_POSTS'] = \ob_get_clean();
    }

    public function default(): void
    {
        $user = User::getCurrent();

        if ('en' !== \Locale::getPrimaryLanguage($user->getLocale())) {
            $messageContent = \sprintf(
                    /** TRANSLATORS: %s: Language, most likely English */
                __('The blog is currently only available in %s and not translatable. Please let me know if you have any ideas to improve this.'),
                '<strong>' . \Locale::getDisplayName('en', 'en') . '</strong>'
            );
            $messageHeader = __('Warning');
            $messageType   = MessageType::WARNING;
            $message       = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        }

        parent::render();
    }
}
