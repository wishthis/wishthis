<?php

/**
 * post.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$postSlug      = $_GET['slug'];
$posts         = Blog::getPreviousCurrentNextPostBySlug($postSlug);
$post          = $posts['current'];
$postMediaHTML = isset($post->featured_media) ? Blog::getMediaHTML($post->featured_media) : '';
$postMedia     = isset($post->featured_media) ? Blog::getMedia($post->featured_media)     : new \stdClss();

$page = new Page(__FILE__, $post->title->rendered);

if (isset($postMedia->source_url)) {
    $page->link_preview = $postMedia->source_url;
}

if (isset($post->excerpt->rendered)) {
    $page->description = substr(strip_tags($post->excerpt->rendered), 0, 256);
} else {
    $page->description = substr(strip_tags($post->content->rendered), 0, 256);
}

$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui text container">
        <?= $page->messages() ?>

        <div class="ui segments">
            <div class="ui fitted segment image">
                <?= $postMediaHTML ?>
            </div>

           <div class="ui segment">
                <h1 class="ui header"><?= $page->title ?></h1>

                <div><?= $post->content->rendered ?></div>
            </div>
        </div>

        <div class="ui two columns grid">

            <div class="column">
                <a href="#top" class="ui fluid labeled icon button">
                    <i class="arrow up icon"></i>
                    <?= __('Top') ?>
                </a>
            </div>

            <div class="column">
                <a href="<?= Page::PAGE_BLOG ?>" class="ui fluid labeled icon button">
                    <i class="rss icon"></i>
                    <?= __('Blog') ?>
                </a>
            </div>

        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
