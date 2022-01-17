<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Create a wishlist');

if (isset($_POST['name'])) {
    $database->query('INSERT INTO `wishlists`
        (`user`, `name`) VALUES
        (' . $_SESSION['user']['id'] . ', "' . $_POST['name'] . '")
    ;');

    header('Location: /?page=wishlist-product-add');
    die();
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <form class="ui form" method="post">
                <div class="field">
                    <label>Name</label>
                    <input type="text"
                           name="name"
                           placeholder="<?= getCurrentSeason() ?>"
                           value="<?= getCurrentSeason() ?>"
                    />
                </div>

                <input class="ui primary button" type="submit" value="Create" />
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
