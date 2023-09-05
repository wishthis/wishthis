<?php

/**
 * Template for saved wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Remembered lists'), 1);
$page->header();
$page->bodyStart();
$page->navigation();

$user = User::getCurrent();

$wishlists         = $user->getSavedWishlists();
$wishlists_by_user = array();

foreach ($wishlists as $wishlist_saved) {
    if (!isset($wishlist_saved['user'])) {
        continue;
    }

    $wishlists_by_user[$wishlist_saved['user']][] = $wishlist_saved;
}
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php if (!empty($wishlists_by_user)) { ?>
            <?php foreach ($wishlists_by_user as $wishlist_user => $wishlists_saved) { ?>
                <?php
                $wishlist_user = User::getFromID($wishlist_user);
                ?>
                <h2 class="ui header"><?= $wishlist_user->getDisplayName() ?></h2>

                <?php if (!empty($wishlists_saved)) { ?>
                    <div class="ui four column doubling stackable grid wishlists-saved">
                        <?php foreach ($wishlists_saved as $wishlist_saved) { ?>
                            <?php
                            $wishlist      = Wishlist::getFromId($wishlist_saved['wishlist']);
                            $wishlist_href = Page::PAGE_WISHLIST . '&hash=' . $wishlist->getHash();
                            ?>

                            <div class="column">
                                <a class="header" href="<?= $wishlist_href ?>">
                                    <div class="ui rounded bordered fluid image">
                                        <?= file_get_contents(ROOT . '/' . Wish::NO_IMAGE) ?>
                                    </div>
                                </a>

                                <div class="content">
                                    <a class="header" href="<?= $wishlist_href ?>"><?= $wishlist->getTitle(); ?></a>
                                    <div class="description"><?= $wishlist_user->getDisplayName(); ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <?= Page::info(__('Ask somebody to share their wishlist with you and hit the remember button for it to show up here!'), __('No lists')); ?>
        <?php } ?>

    </div>
</main>

<?php
$page->bodyEnd();
