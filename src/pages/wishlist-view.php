<?php

/**
 * Template for viewing wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User, Wishlist};

$page = new page(__FILE__, 'View wishlist');
$page->header();
$page->navigation();

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

                <div class="field">
                    <select class="ui fluid search selection dropdown loading wishlists" name="wishlist">
                        <option value="">Loading your wishlists...</option>
                    </select>
                </div>
            </div>

            <div class="ui segment">
                <h2 class="ui header">Options</h1>
                <p>Wishlist related options.</p>

                <a class="ui small labeled icon button wishlist-share disabled" target="_blank">
                    <i class="share icon"></i>
                    Share
                </a>

                <form class="ui form wishlist-delete" method="post" style="display: inline-block;">
                    <input type="hidden" name="wishlist_delete_id" />

                    <button class="ui small labeled red icon button disabled" type="submit">
                        <i class="trash icon"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="wishlist-cards"></div>
    </div>
</main>

<?php
$page->footer();
