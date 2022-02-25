<?php

/**
 * The home page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');
$page->header();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header">Welcome to wishthis</h2>
            <p>
                wishthis is a simple, intuitive and modern platform to create,
                manage and view your wishes for any kind of occasion.
            </p>

            <div class="ui four column doubling stackable grid">
                <?php if ($user->isLoggedIn()) { ?>
                    <div class="column"><a class="ui fluid primary button" href="/?page=wishlists">Create a wishlist</a></div>
                    <div class="column"><a class="ui fluid button" href="/?page=wishlists">View your wishlists</a></div>
                <?php } else { ?>
                    <div class="column"><a class="ui fluid primary button" href="/?page=register">Register now</a></div>
                    <div class="column"><a class="ui fluid button" href="/?page=login">Login</a></div>
                <?php } ?>
            </div>

            <h2 class="ui header">Use case</h2>
            <p>
                Your birthday is coming up and you just created a wishlist with
                all the cool stuff you want. Your friends and family want to
                make sure you get something you are happy with so you send them
                your wishlist link and if anybody decides to get an item for
                you, they simply commit to buying it and the item disappears for
                everybody else.
            </p>
        </div>
    </div>
</main>

<?php
$page->footer();
?>
