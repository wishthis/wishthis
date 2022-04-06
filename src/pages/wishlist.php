<?php

/**
 * Template for viewing a wishlist directly via its link.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User, Wishlist};

$wishlist = new Wishlist($_GET['wishlist']);

if (!$wishlist->exists) {
    http_response_code(404);
    ?>
    <h1><?= __('Not found') ?></h1>
    <p><?= __('The requested Wishlist was not found and likely deleted by its creator.') ?></p>
    <?php
    die();
}

$page = new Page(__FILE__, $wishlist->name);
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php if ($user->isLoggedIn() && $user->id !== intval($wishlist->user)) { ?>
            <button class="ui white small basic labeled icon button save">
                <i class="heart icon"></i>
                <span><?= __('Save list') ?></span>
            </button>
        <?php } ?>

        <?php
        /**
         * Warn the wishlist creator
         */
        if (isset($user->id) && $user->id === intval($wishlist->user) && !empty($wishlist->wishes)) { ?>
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
                    __('If you found a wish you would like to fulfil, click the %s button and it will become unavailable for others.'),
                    '<span class="ui primary tiny horizontal label"><i class="gift icon"></i> ' . __('Fulfil wish') . '</span>'
                ) ?></p>
            </div>
        </div>

        <h2 class="ui header"><?= __('Wishes') ?></h2>

        <div class="wishlist-cards">
            <?php
            echo $wishlist->getCards(
                array(
                    'WHERE' => '`wishlist` = ' . $wishlist->id . ' AND (`status` != "unavailable" OR `status` IS NULL)',
                )
            );
            ?>
        </div>

    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
