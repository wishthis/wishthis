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

                    <div class="ui labeled icon top left pointing dropdown button options"
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

        <?php include 'parts/wishlist-filter.php' ?>

        <div class="wishlist-cards"></div>
    </div>
</main>

<!-- Modal: Default -->
<div class="ui modal default">
    <div class="header"></div>
    <div class="content"></div>
    <div class="actions"></div>
</div>

<!-- Wishlist: Create -->
<div class="ui modal wishlist-create">
    <div class="header">
        <?= __('Create a wishlist') ?>
    </div>
    <div class="content">
        <div class="description">
            <p><?= __('Choose a new name for your wishlist. Here\'s a suggestion to get you started.') ?></p>

            <form class="ui form">
                <div class="field">
                    <label><?= __('Name') ?></label>
                    <input type="text"
                           name="wishlist-name"
                           data-default="<?= getWishlistNameSuggestion() ?>"
                    />
                </div>
            </form>
        </div>
    </div>
    <div class="actions">
        <div class="ui approve primary button create" title="<?= __('Create') ?>">
            <?= __('Create') ?>
        </div>
        <div class="ui deny button cancel" title="<?= __('Cancel') ?>">
            <?= __('Cancel') ?>
        </div>
    </div>
</div>

<!-- Wishlist: Rename -->
<div class="ui tiny modal wishlist-rename">
    <div class="header">
        <?= __('Rename wishlist') ?>
    </div>
    <div class="content">
        <p><?= __('How would you like to name this wishlist?') ?></p>

        <form class="ui form wishlist-rename">
            <input type="hidden" name="wishlist_id" />

            <div class="field">
                <label><?= __('Title') ?></label>
                <input type="text" name="wishlist_title" maxlength="128" />
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui approve primary button" title="<?= __('Rename') ?>">
            <?= __('Rename') ?>
        </div>
        <div class="ui deny button" title="<?= __('Cancel') ?>">
            <?= __('Cancel') ?>
        </div>
    </div>
</div>

<!-- Wishlist: Add a wish -->
<div class="ui modal wishlist-wish-add">
    <div class="header">
        <?= __('Add a wish') ?>
    </div>
    <div class="content">
        <div class="description">
            <p><?= __('Fill the title and/or description to add your new wish. If you just fill out the URL, wishthis will attempt to auto fill all other fields.') ?></p>

            <form class="ui form wishlist-wish-add" method="POST">
                <input type="hidden" name="wishlist_id" />

                <?php include 'parts/wish-add.php' ?>

                <div class="ui error message"></div>
            </form>
        </div>
    </div>
    <div class="actions">
        <div class="ui primary approve button" title="<?= __('Add') ?>">
            <?= __('Add') ?>
        </div>
        <div class="ui deny button" title="<?= __('Cancel') ?>">
            <?= __('Cancel') ?>
        </div>
    </div>
</div>

<!-- Wishlist: Edit a wish -->
<div class="ui modal wishlist-wish-edit">
    <div class="header">
        <?= __('Edit wish') ?>
    </div>
    <div class="content">
        <div class="description">
            <p><?= __('If specified, wishthis will attempt to fetch all missing information from the URL.') ?></p>

            <form class="ui form wishlist-wish-edit" method="POST">
                <input type="hidden" name="wishlist_id" />
                <input type="hidden" name="wish_id" />

                <?php include 'parts/wish-add.php' ?>

                <div class="ui error message"></div>
            </form>
        </div>
    </div>
    <div class="actions">
        <div class="ui primary approve button" title="<?= __('Save') ?>">
            <?= __('Save') ?>
        </div>
        <div class="ui deny button" title="<?= __('Cancel') ?>">
            <?= __('Cancel') ?>
        </div>
    </div>
</div>

<!-- Wish: Validate -->
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
                    <input class="ui input current disabled" type="url" readonly />
                </div>

                <div class="field">
                    <label><?= __('Proposed') ?></label>
                    <input class="ui input proposed" type="url" />
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
$page->bodyEnd();
