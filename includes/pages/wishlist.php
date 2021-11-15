<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');

if (isset($_POST['email'], $_POST['password'])) {
    $user = $database->query(
        'SELECT * FROM `users`
         WHERE `email` = "' . $_POST['email'] . '"
         AND `password` = "' . sha1($_POST['password']) . '";'
    )->fetch();

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
            <input type="name" name="name" placeholder="<?= getCurrentSeason() ?>" />
        </fieldset>

        <fieldset>
            <label>Password</label>
            <input type="password" name="password" />
        </fieldset>

        <input type="submit" value="Create" />
    </form>
</section>
</main>

<?php
$page->footer();
