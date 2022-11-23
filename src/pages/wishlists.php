<?php

/**
 * Template for wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('My lists'));
$page->header();
$page->bodyStart();
$page->navigation();

?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <div class="ui form">
                <div class="two fields">
                    <div class="field">
                        <label><?= __('Wishlist') ?></label>

                        <select class="ui fluid search selection dropdown loading wishlists" name="wishlist">
                            <option value=""><?= __('Loading your wishlists...') ?></option>
                        </select>
                    </div>

                    <div class="field">
                        <label><?= __('Options') ?></label>

                        <div class="flex buttons">
                            <a class="ui labeled icon button wishlist-create"
                               title="<?= __('Create a wishlist') ?>"
                            >
                                <i class="add icon"></i>
                                <?= __('Create a wishlist') ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex buttons">
                    <a class="ui labeled icon primary button wishlist-wish-add disabled"
                        title="<?= __('Add a wish') ?>"
                    >
                        <i class="add icon"></i>
                        <?= __('Add a wish') ?>
                    </a>

                    <a class="ui labeled icon button wishlist-share disabled"
                        target="_blank"
                        title="<?= __('Share') ?>"
                    >
                        <i class="share icon"></i>
                        <?= __('Share') ?>
                    </a>

                    <div class="ui labeled icon top left pointing dropdown disabled button wishlist-options"
                            title="<?= __('Options') ?>"
                    >
                        <i class="cog icon"></i>
                        <span class="text"><?= __('Options') ?></span>
                        <div class="menu">

                            <div class="item wishlist-rename disabled" title="<?= __('Rename') ?>">
                                <i class="pen icon"></i>
                                <?= __('Rename') ?>
                            </div>

                            <div class="item wishlist-delete disabled" title="<?= __('Delete') ?>">
                                <i class="trash icon"></i>
                                <?= __('Delete') ?>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <h2 class="ui header"><?= __('Wishes') ?></h2>

        <div class="ui primary progress">
            <div class="bar">
                <div class="progress"></div>
            </div>
        </div>

        <?php include 'parts/wishlist.php' ?>
    </div>
</main>

<!-- Modal: Default -->
<div class="ui modal default">
    <div class="header"></div>
    <div class="content"></div>
    <div class="actions"></div>
</div>

<?php
$page->bodyEnd();
