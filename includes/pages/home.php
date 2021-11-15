<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');
$page->header();
?>

<main>
    <section>
        <h1>Welcome to wishthis</h1>
        <a href="?page=register">Register</a>
        <a href="?page=login">Login</a>
    </section>

    <section>
        <h2>Wishlist</h2>
        <a href="?page=wishlist">Create a wishlist</a>
    </section>
</main>

<?php
$page->footer();
?>
