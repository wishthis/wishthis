<?php

/**
 * home.php
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
            <p>
                Go ahead and get started now and <a href="/?page=wishlist-create">create a wishlist</a>!
            </p>

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
