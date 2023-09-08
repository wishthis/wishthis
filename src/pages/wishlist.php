<?php

/**
 * Template for viewing a wishlist directly via its link.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$wishlist = Wishlist::getFromHash($_GET['hash']);

if (false === $wishlist) {
    $page = new Page(__FILE__, 'Wishlist not found');
    $page->errorDocument(404, Wishlist::class);
}

$wishlist_user                             = User::getFromID($wishlist->getUserId());
$page                                      = new Page(__FILE__, $wishlist->getTitle());
$page->stylesheets['wish']                 = 'src/assets/css/wish.css';
$page->stylesheets['wish-card']            = 'src/assets/css/wish-card.css';
$page->scripts['wish']                     = 'src/assets/js/parts/wish.js';
$page->scripts['wishlist-filter-priority'] = 'src/assets/js/parts/wishlist-filter-priority.js';
$page->scripts['wishlists']                = 'src/assets/js/parts/wishlists.js';
$page->header();
$page->bodyStart();
$page->navigation();

$user = User::getCurrent();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui stackable grid">
            <div class="column">

                <?php if ($user->isLoggedIn() && $user->getId() !== $wishlist->getUserId()) { ?>
                    <button class="ui white small basic labeled icon button save disabled loading">
                        <i class="heart icon"></i>
                        <span><?= __('Remember list') ?></span>
                    </button>
                <?php } ?>

            </div>
        </div>

        <?php
        /**
         * Warn the wishlist creator
         */
        if ($user->isLoggedIn() && $user->getId() === $wishlist->getUserId()) { ?>
            <div class="ui icon warning message wishlist-own">
                <i class="exclamation triangle icon"></i>
                <div class="content">
                    <div class="header">
                        <?= __('Careful') ?>
                    </div>
                    <div class="text">
                        <p><?= __('You are viewing your own wishlist! You will be able to see which wishes have already been fulfilled for you. Don\'t you want to be surprised?') ?></p>
                        <p><?= __('It\'s probably best to just close this tab.') ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="ui segments">
            <div class="ui segment">
                <h2 class="ui header"><?= __('What to do?') ?></h2>
                <p><?= sprintf(
                    __('If you found a wish you would like to fulfil, open the wish %s and then click the %s button and it will be unavailable for everybody else.'),
                    '<span class="ui tiny horizontal label"><i class="stream icon"></i> ' . __('Details') . '</span>',
                    '<span class="ui primary tiny horizontal label"><i class="gift icon"></i> ' . __('Fulfil wish') . '</span>'
                ) ?></p>
            </div>
        </div>

        <h2 class="ui header"><?= __('Wishes') ?></h2>

        <?php include 'parts/wishlist.php' ?>

        <div class="ui basic center aligned segment">
            <button class="ui primary button wishlist-request-wishes" data-locale="<?= $wishlist_user->getLocale() ?>">
                <?= __('Request more wishes') ?>
            </button>
        </div>

    </div>
</main>

<?php
$page->bodyEnd();
?>

<!-- Wishlist: Request wishes -->
<div class="ui tiny modal wishlist-request-wishes-notification-sent">
    <div class="header">
        <?= __('Request more wishes') ?>
    </div>
    <div class="content">
        <div class="description">
            <p><?= __('A notification has just been sent to the owner of this wishlist.') ?></p>
        </div>
    </div>
    <div class="actions">
        <div class="ui approve primary button" title="<?= __('Ok') ?>">
            <?= __('Ok') ?>
        </div>
    </div>
</div>
<div class="ui tiny modal wishlist-request-wishes-notification-notsent">
    <div class="header">
        <?= __('Request more wishes') ?>
    </div>
    <div class="content">
        <div class="description">
            <p><?= __('The wishlist owner has already received a notification recently and has not been notified again.') ?></p>
        </div>
    </div>
    <div class="actions">
        <div class="ui approve primary button" title="<?= __('Ok') ?>">
            <?= __('Ok') ?>
        </div>
    </div>
</div>
