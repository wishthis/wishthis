<?php

/**
 * Template for saved wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Remembered lists'));
$page->header();
$page->bodyStart();
$page->navigation();

$wishlists = $user->getSavedWishlists();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php if (!empty($wishlists)) { ?>
            <div class="ui four column doubling stackable grid wishlists-saved">
                <?php foreach ($wishlists as $wishlist_saved) { ?>
                    <?php
                    $wishlist      = new Wishlist($wishlist_saved['wishlist']);
                    $wishlist_user = new User($wishlist_saved['user']);
                    $wishlist_href = Page::PAGE_WISHLIST . '&hash=' . $wishlist->hash;
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
        <?php } else { ?>
            <?= Page::info(__('Ask somebody to share their wishlist with you and hit the remember button for it to show up here!'), __('No lists')); ?>
        <?php } ?>

    </div>
</main>

<?php
$page->bodyEnd();
