<?php

/**
 * Template for viewing a wish.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$userIsAuthenticated = false;

$wish = new Wish($_SESSION['_GET']['id'], false);
$page = new Page(__FILE__, $wish->getTitle());

if ('POST' === $_SERVER['REQUEST_METHOD'] && count($_POST) >= 0) {
    $wish_id             = $_POST['wish_id'];
    $wish_title          = trim($_POST['wish_title']);
    $wish_description    = $_POST['wish_description'] ?: '';
    $wish_image          = trim($_POST['wish_image']);
    $wish_url            = trim($_POST['wish_url']);
    $wish_priority       = isset($_POST['wish_priority']) && $_POST['wish_priority'] ? $_POST['wish_priority'] : 'NULL';
    $wish_is_purchasable = isset($_POST['wish_is_purchasable']) ? 'TRUE' : 'FALSE';

    $database
    ->query('UPDATE `wishes`
                SET `title`          = "' . $wish_title . '",
                    `description`    = "' . $wish_description . '",
                    `image`          = "' . $wish_image . '",
                    `url`            = "' . $wish_url . '",
                    `priority`       = ' . $wish_priority . ',
                    `is_purchasable` = ' . $wish_is_purchasable . '
              WHERE `id`             = ' . $wish_id . ';');

    $wish             = new Wish($_SESSION['_GET']['id'], false);
    $page             = new Page(__FILE__, $wish->getTitle());
    $page->messages[] = Page::success(__('Wish successfully updated.'), __('Success'));
}

if (!$wish->exists) {
    $page->errorDocument(404, $wish);
}

$wishlists = $user->getWishlists($wish->wishlist);

foreach ($wishlists as $wishlist) {
    if ($wish->wishlist === intval($wishlist['id'])) {
        $userIsAuthenticated = true;
        break;
    }
}

if (!$userIsAuthenticated) {
    $page->errorDocument(404, $wish);
}

$page->header();
$page->bodyStart();
$page->navigation();

$referer = '/?page=wishlists&id=' . $wish->wishlist;
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages() ?>

        <div class="ui grid">
            <div class="row">
                <div class="sixteen wide column">

                    <?php if ($wish->image) { ?>
                        <img class="ui fluid rounded image preview" src="<?= $wish->image ?>" />
                    <?php } ?>

                </div>
            </div>

            <div class="row">
                <div class="sixteen wide column">

                    <a class="ui labeled icon button"
                       href="<?= $wish->url ?>"
                       target="_blank"
                       title="<?= __('Visit') ?>"
                    >
                        <i class="external icon"></i>
                        <?= __('Visit') ?>
                    </a>

                    <button class="ui labeled icon button auto-fill disabled"
                            type="button"
                            title="<?= __('Auto-fill') ?>"
                    >
                        <i class="redo icon"></i>
                        <?= __('Auto-fill') ?>
                    </button>

                </div>
            </div>
        </div>

        <div class="ui segment">
            <form class="ui form wish" method="POST">
                <input type="hidden" name="wish_id" value="<?= $_SESSION['_GET']['id'] ?>" />
                <input type="hidden" name="wish_image" value="<?= $wish->image ?>" />

                <div class="ui two column grid">
                    <?php include 'parts/wish-add.php' ?>

                    <div class="stackable row">
                        <div class="sixteen wide column">

                            <input class="ui primary button"
                                   type="submit"
                                   value="<?= __('Save') ?>"
                                   title="<?= __('Save') ?>"
                            />
                            <input class="ui button"
                                   type="reset"
                                   value="<?= __('Reset') ?>"
                                   title="<?= __('Reset') ?>"
                            />
                            <a class="ui secondary button"
                               href="<?= $referer ?>"
                               title="<?= __('Back') ?>"
                            >
                                <?= __('Back') ?>
                            </a>

                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</main>

<!-- Preview -->
<div class="ui small modal preview">
    <div class="header">
        <?= __('Image') ?>
    </div>
    <div class="content">
        <p><?= __('Define a new URL to be used as a preview.') ?></p>

        <div class="ui form preview">
            <div class="field">
                <label><?= __('URL') ?></label>
                <input class="current" type="url" name="wish_image" />
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui primary approve button" title="<?= __('Save') ?>">
            <?= __('Save') ?>
        </div>
        <div class="ui deny button" title="<?= __('Discard') ?>">
            <?= __('Discard') ?>
        </div>
    </div>
</div>

<!-- Auto-fill -->
<div class="ui small modal auto-fill">
    <div class="header">
        <?= __('Warning') ?>
    </div>
    <div class="content">
        <p><?= __('This action will potentially overwrite all fields in this wish.') ?></p>
        <p><?= __('Would you like to continue?') ?></p>
    </div>
    <div class="actions">
        <div class="ui primary approve button" title="<?= __('Yes, overwrite') ?>">
            <?= __('Yes, overwrite') ?>
        </div>
        <div class="ui deny button" title="<?= __('No') ?>">
            <?= __('No') ?>
        </div>
    </div>
</div>

<!-- Validate -->
<div class="ui small modal validate">
    <div class="header">
        <?= __('URL mismatch') ?>
    </div>
    <div class="content">
        <div class="description">
            <p><?= __('The URL you have entered does not seem quite right. Would you like to update it with the one I found?') ?></p>
            <p class="provider"><?= sprintf(__('According to %s, this is the canonical (correct) URL.'), '<strong class="providerName">Unknown</strong>') ?></p>

            <div class="ui form urls">
                <div class="field">
                    <label><?= __('Current') ?></label>
                    <input class="current" type="url" readonly />
                </div>

                <div class="field">
                    <label><?= __('Proposed') ?></label>
                    <input class="proposed" type="url" />
                </div>
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui primary approve button" title="<?= __('Yes, update') ?>">
            <?= __('Yes, update') ?>
        </div>
        <div class="ui deny button" title="<?= __('No, leave it') ?>">
            <?= __('No, leave it') ?>
        </div>
    </div>
</div>

<?php
$page->footer();
$page->bodyEnd();
?>
