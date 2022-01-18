<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

if (isset($_POST['url'], $_POST['wishlist'])) {
    $database->query('INSERT INTO `products`
        (`wishlist`, `url`) VALUES
        (' . $_POST['wishlist'] . ', "' . $_POST['url'] . '")
    ;');
}

$page = new page(__FILE__, 'Add a product');
$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <div class="ui segment">
            <h1 class="ui header"><?= $page->title ?></h1>

            <form class="ui form" method="post">
                <div class="field">
                    <label>URL</label>
                    <input type="url" name="url" />
                </div>

                <div class="field">
                    <select class="ui search selection dropdown loading wishlists" name="wishlist">
                        <option value="">Loading your wishlists...</option>
                    </select>
                </div>

                <input class="ui primary button" type="submit" value="Add" />
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
