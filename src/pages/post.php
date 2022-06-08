<?php

/**
 * post.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$postSlug      = $_SESSION['_GET']['slug'];
$post          = Blog::getPostBySlug($postSlug);
$postMediaHTML = isset($post->featured_media) ? Blog::getMediaHTML($post->featured_media) : '';

$page = new Page(__FILE__, 'Post');
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui text container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segments">
            <div class="ui fitted segment image">
                <?= $postMediaHTML ?>
            </div>

           <div class="ui segment">
                <h2 class="ui header"><?= $post->title->rendered ?></h2>

                <div><?= $post->content->rendered ?></div>
            </div>
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
