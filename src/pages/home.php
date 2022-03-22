<?php

/**
 * The home page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new Page(__FILE__, 'Home');
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Welcome to wishthis') ?></h2>
            <p>
                wishthis is a simple, intuitive and modern wishlist platform to create,
                manage and view your wishes for any kind of occasion.
            </p>

            <div class="ui four column doubling stackable centered grid">
                <?php if ($user->isLoggedIn()) { ?>
                    <div class="column">
                        <a class="ui fluid primary button" href="/?page=wishlists">Create a wishlist</a>
                    </div>
                    <div class="column">
                        <a class="ui fluid button" href="/?page=wishlists">View your wishlists</a>
                    </div>
                <?php } else { ?>
                    <div class="column">
                        <a class="ui fluid primary button" href="/?page=register">Register now</a>
                    </div>
                    <div class="column">
                        <a class="ui fluid button" href="/?page=login">Login</a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="ui segment">
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
            <h2 class="ui header">Why wishthis?</h2>
            <p>
                wishthis is free and open source software.
                With free I don't just mean, you don't have to pay money to use it,
                but you are also not paying with your personal information and behaviour.

                Not only can anybody
                <a href="https://github.com/grandeljay/wishthis"
                   title="wishthis source code"
                   target="_blank"
                >view and verify its code</a>,
                I also encourage you to do so.
            </p>

            <p>As a non-commercial project it remains</p>
            <div class="ui list">
                <div class="item">
                    <i class="green check icon"></i>
                    <div class="content">free of advertisements,</div>
                </div>
                <div class="item">
                    <i class="green check icon"></i>
                    <div class="content">without tracking, and</div>
                </div>
                <div class="item">
                    <i class="green check icon"></i>
                    <div class="content">open for feedback and suggestions.</div>
                </div>
            </div>

            <h3>Join the others and get started now!</h3>
            <div class="ui stackable statistics">

                <div class="statistic" id="wishes">
                    <div class="value">N. A.</div>
                    <div class="label">Wishes</div>
                </div>

                <div class="statistic" id="wishlists">
                    <div class="value">N. A.</div>
                    <div class="label">Wishlists</div>
                </div>

                <div class="statistic" id="users">
                    <div class="value">N. A.</div>
                    <div class="label">Registered users</div>
                </div>

            </div>

        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
