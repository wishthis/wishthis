<?php

/**
 * Template for saved wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User, Wishlist};

$page = new Page(__FILE__, __('Saved lists'));
$page->header();
$page->bodyStart();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <div class="ui relaxed divided list">

                <?php foreach ($user->getSavedWishlists() as $wishlist_saved) { ?>
                    <?php
                    $wishlist      = new Wishlist($wishlist_saved['wishlist']);
                    $wishlist_user = new User($wishlist_saved['user']);
                    ?>
                    <div class="item">
                        <i class="large heart middle aligned icon"></i>
                        <div class="content">
                            <a class="header" href="/?wishlist=<?= $wishlist->hash ?>"><?= $wishlist->getTitle(); ?></a>
                            <div class="description"><?= $wishlist_user->getDisplayName(); ?></div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
