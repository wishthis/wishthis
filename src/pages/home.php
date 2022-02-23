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
            <?php if ($user->isLoggedIn()) { ?>
                <p>
                    <a class="ui primary button" href="/?page=wishlists">Create a wishlist</a>
                    <a class="ui button" href="/?page=wishlists">View your wishlists</a>
                </p>
            <?php } else { ?>
                <p>
                    <a class="ui primary button" href="/?page=register">Register now</a>
                    <a class="ui button" href="/?page=login">Login</a>
                </p>
            <?php } ?>

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

        <div class="ui segment">
            <h2 class="ui header">Statistics</h2>

            <div class="ui statistics">
                <div class="statistic">
                    <div class="value">22</div>
                    <div class="label">Products</div>
                </div>
                <div class="statistic">
                    <div class="value">31,200</div>
                    <div class="label">Wishlists</div>
                </div>
                <div class="statistic">
                    <div class="value">22</div>
                    <div class="label">Registered users</div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$page->footer();
?>
