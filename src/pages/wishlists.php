<?php

/**
 * Template for wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User, Wishlist};

$page = new page(__FILE__, 'Wishlists');
$page->header();
$page->navigation();

/**
 * Create
 */
if (isset($_POST['wishlist-create'], $_POST['name'])) {
    $database->query('INSERT INTO `wishlists`
        (
            `user`,
            `name`,
            `hash`
        ) VALUES (
            ' . $_SESSION['user']['id'] . ',
            "' . $_POST['name'] . '",
            "' . sha1(time() . $_SESSION['user']['id'] . $_POST['name']) . '"
        )
    ;');

    header('Location: /?page=wishlist-product-add');
    die();
}

/**
 * Delete
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

        <div class="ui segment">
            <h2 class="ui header">Create</h2>
            <p>
                Choose a new name for your wishlist.
                Here's a suggestion to get you started!
            </p>

            <form class="ui form" method="post">
                <div class="field">
                    <label>Name</label>
                    <input type="text"
                           name="name"
                           placeholder="<?= getCurrentSeason() ?>"
                           value="<?= getCurrentSeason() ?>"
                    />
                </div>

                <input class="ui primary button"
                       type="submit"
                       name="wishlist-create"
                       value="Create"
                />
            </form>
        </div>

        <div class="ui horizontal stackable segments">
            <div class="ui segment">
                <h2 class="ui header">View</h2>
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
    </div>

    <div class="wishlist-cards"></div>
</main>

<?php
$page->footer();
