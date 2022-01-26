<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User, Wishlist};

$page = new page(__FILE__, 'View wishlist');
$page->header();
$page->navigation();

/**
 * Get wishlist products
 */
if (isset($_GET['wishlist'])) {
    $wishlist = new Wishlist(intval($_GET['wishlist']));
}

/**
 * Delete wishlist
 */
if (isset($_POST['wishlist_delete_id'])) {
    $database->query('DELETE FROM `wishlists`
        WHERE id = ' . $_POST['wishlist_delete_id'] . '
    ;');
}
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui horizontal stackable segments">
            <div class="ui segment">
                <h2 class="ui header">Wishlists</h2>
                <p>Please select a wishlist to view.</p>

                <form class="ui form" method="get">
                    <input type="hidden" name="page" value="wishlist-view" />

                    <div class="field">
                        <select class="ui search selection dropdown loading wishlists" name="wishlist">
                            <option value="">Loading your wishlists...</option>
                        </select>
                    </div>

                    <input class="ui primary button wishlist-view disabled" type="submit" value="View" />
                </form>
            </div>

            <div class="ui segment">
                <h2 class="ui header">Options</h1>
                <p>Wishlist related options.</p>

                <a class="ui small labeled icon button wishlist-share <?= !isset($_GET['wishlist']) ? 'disabled' : '' ?>" href="/?wishlist=<?= $wishlist->data['hash'] ?? '' ?>" target="_blank">
                    <i class="share icon"></i>
                    Share
                </a>

                <form class="ui form wishlist-delete" method="post" style="display: inline-block;">
                    <input type="hidden" name="wishlist_delete_id" />

                    <button class="ui small labeled red icon button <?= !isset($_GET['wishlist']) ? 'disabled' : '' ?>" type="submit">
                        <i class="trash icon"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <?php
        if (isset($_GET['wishlist'])) {
            $wishlist->getCards();
        }
        ?>
    </div>
</main>

<?php
$page->footer();
