<?php

/**
 * The home page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new Page(__FILE__, __('Home'));
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Welcome to wishthis') ?></h2>
            <p><?= __('wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.') ?></p>

            <div class="ui four column doubling stackable centered grid">
                <?php if ($user->isLoggedIn()) { ?>
                    <div class="column">
                        <a class="ui fluid primary button" href="/?page=wishlists"><?= __('Create a wishlist') ?></a>
                    </div>
                    <div class="column">
                        <a class="ui fluid button" href="/?page=wishlists"><?= __('View your wishlists') ?></a>
                    </div>
                <?php } else { ?>
                    <div class="column">
                        <a class="ui fluid primary button" href="/?page=register"><?= __('Register now') ?></a>
                    </div>
                    <div class="column">
                        <a class="ui fluid button" href="/?page=login"><?= __('Login') ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Use case') ?></h2>
            <p><?= __('Your birthday is coming up and you just created a wishlist with all the cool stuff you want. Your friends and family want to make sure you get something you are happy with so you send them your wishlist link and if anybody decides to gift something to you, they simply commit to the wish and it will disappear for everybody else.') ?></p>
        </div>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Why wishthis?') ?></h2>
            <p><?= sprintf(
                __('wishthis is free and open source software. With free I don\'t just mean, you don\'t have to pay money to use it, but you are also not paying with your personal information and behaviour. Not only can anybody %sview and verify its code%s, I also encourage you to do so.'), '<a href="https://github.com/grandeljay/wishthis" title="wishthis source code" target="_blank">', '</a>'
            ) ?></p>

            <p><?= __('As a non-commercial project it remains') ?></p>
            <div class="ui list">
                <div class="item">
                    <i class="green check icon"></i>
                    <div class="content"><?= __('free of advertisements,') ?></div>
                </div>
                <div class="item">
                    <i class="green check icon"></i>
                    <div class="content"><?= __('without tracking, and') ?></div>
                </div>
                <div class="item">
                    <i class="green check icon"></i>
                    <div class="content"><?= __('open for feedback and suggestions.') ?></div>
                </div>
            </div>

            <h3><?= __('Join the others and get started now!') ?></h3>
            <div class="ui stackable statistics">

                <div class="statistic" id="wishes">
                    <div class="value"><?= __('N. A.') ?></div>
                    <div class="label"><?= __('Wishes') ?></div>
                </div>

                <div class="statistic" id="wishlists">
                    <div class="value"><?= __('N. A.') ?></div>
                    <div class="label"><?= __('Wishlists') ?></div>
                </div>

                <div class="statistic" id="users">
                    <div class="value"><?= __('N. A.') ?></div>
                    <div class="label"><?= __('Registered users') ?></div>
                </div>

            </div>

        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
