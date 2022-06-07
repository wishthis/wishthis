<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Blog'));
$page->header();
$page->bodyStart();
$page->navigation();

$posts = Blog::getPosts();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages() ?>

        <?php foreach ($posts as $post) { ?>
            <div class="ui segment">
                <h2 class="ui header"><?= $post->title->rendered ?></h2>

                <div><?= $post->content->rendered ?></div>
            </div>
        <?php } ?>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
