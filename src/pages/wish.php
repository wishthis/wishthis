<?php

/**
 * Template for viewing a wish.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, Wish};

$messages = array();

if ('POST' === $_SERVER['REQUEST_METHOD'] && count($_POST) >= 0) {
    $database
    ->query('UPDATE `wishes`
                SET `title`       = "' . trim($_POST['wish_title']) . '",
                    `description` = "' . trim($_POST['wish_description']) . '",
                    `url`         = "' . trim($_POST['wish_url']) . '"
              WHERE `id`          = ' . trim($_POST['wish_id']) . ';');

    $messages[] = Page::success('Wish successfully updated.', 'Success');
}

$wish = new Wish($_GET['id'], false);

/*
if (!$wish->exists()) {
    http_response_code(404);
    ?>
    <h1>Not found</h1>
    <p>The requested Wish was not found.</p>
    <?php
    die();
}
*/

$page = new page(__FILE__, $wish->title);
$page->header();
$page->navigation();

$referer = '/?page=wishlists&wishlist=' . $wish->wishlist;
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages($messages) ?>

        <div class="ui segment">
            <form class="ui form" method="POST">
                <input type="hidden" name="wish_id" value="<?= $_GET['id'] ?>">

                <div class="ui two column grid">

                    <div class="stackable row">
                        <div class="column">
                            <?php if ($wish->info->image) { ?>
                                <img class="ui fluid rounded image" src="<?= $wish->info->image ?>" />
                            <?php } ?>
                        </div>

                        <div class="column">
                            <div class="ui header">Options</div>

                            <a class="ui labeled icon button" href="<?= $wish->url ?>" target="_blank">
                                <i class="external icon"></i>
                                Visit
                            </a>
                        </div>
                    </div>

                    <div class="one column row">
                        <div class="column">

                            <div class="field">
                                <label>Title</label>

                                <div class="ui input">
                                    <input type="text"
                                           name="wish_title"
                                           placeholder="<?= $wish->title ?>"
                                           value="<?= $wish->title ?>"
                                    />
                                </div>
                            </div>

                            <div class="field">
                                <label>Description</label>
                                <textarea name="wish_description"
                                          placeholder="<?= $wish->description ?>"
                                ><?= $wish->description ?></textarea>
                            </div>

                            <div class="field">
                                <label>URL</label>

                                <div class="ui input">
                                    <input type="url"
                                           name="wish_url"
                                           placeholder="<?= $wish->url ?>"
                                           value="<?= $wish->url ?>"
                                    />
                                </div>
                            </div>

                            <input class="ui primary button" type="submit" value="Save" />
                            <input class="ui button" type="reset" value="Reset" />
                            <a class="ui secondary button" href="<?= $referer ?>">Cancel</a>

                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</main>

<?php
$page->footer();
?>
