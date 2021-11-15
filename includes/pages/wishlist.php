<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');

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
?>
<main>
<section>
    <h1>Create a wishlist</h1>

    <form method="post">
        <fieldset>
            <label>Name</label>
            <input type="text" name="name" placeholder="<?= getCurrentSeason() ?>" value="<?= getCurrentSeason() ?>" />
        </fieldset>

        <input type="submit" value="Create" />
    </form>
</section>
</main>

<?php
$page->footer();
