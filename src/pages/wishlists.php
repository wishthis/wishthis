<?php

/**
 * Template for wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page                                      = new Page(__FILE__, __('My lists'), 1);
$page->stylesheets['wish']                 = 'src/assets/css/wish.css';
$page->stylesheets['wish-card']            = 'src/assets/css/wish-card.css';
$page->scripts['wish']                     = 'src/assets/js/parts/wish.js';
$page->scripts['wishlist-filter-priority'] = 'src/assets/js/parts/wishlist-filter-priority.js';
$page->scripts['wishlists']                = 'src/assets/js/parts/wishlists.js';
$page->header();
$page->bodyStart();
$page->navigation();

?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages() ?>

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
