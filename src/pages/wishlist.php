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
    <h1>Not found</h1>
    <p>The requested Wishlist was not found and likely deleted by its creator.</p>
    <?php
    die();
}

$page = new page(__FILE__, $wishlist->data['name']);
$page->header();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php
        /**
         * Warn the wishlist creator
         */
        if (isset($user->id) && $user->id === intval($wishlist->data['user']) && !empty($wishlist->products)) { ?>
            <div class="ui icon warning message wishlist-own">
                <i class="exclamation triangle icon"></i>
                <div class="content">
                    <div class="header">
                        Careful
                    </div>
                    <div class="text">
                        <p>
                            You are viewing your own wishlist!
                            You will be able to see which products have already been bought for you.
                            Don't you want to be surprised?
                        </p>
                        <p>
                            It's probably best to just close this tab.
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="ui segment">
            <h2 class="ui header">What to do?</h2>
            <p>
                If you found something you would like to buy,
                click the <span class="ui tiny horizontal label">Commit</span> button
                and it will become unavailable for others.
            </p>
        </div>

        <div class="wishlist-cards">
            <?php
            echo $wishlist->getCards(
                array(
                    'exclude' => array('unavailable'),
                )
            );
            ?>
        </div>
    </div>
</main>

<?php
$page->footer();
?>
