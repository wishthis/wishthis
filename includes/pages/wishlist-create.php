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

    $_SESSION['user'] = $user;

    header('Location: ?page=home');
    die();
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <div class="ui segment">
            <h1 class="ui header">Create a wishlist</h1>
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
