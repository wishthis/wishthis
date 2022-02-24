<?php

/**
 * Add product to a wishlist.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

if (isset($_POST['url'], $_POST['wishlist'])) {
    $database->query('INSERT INTO `products`
        (`wishlist`, `url`) VALUES
        (' . $_POST['wishlist'] . ', "' . $_POST['url'] . '")
    ;');
}

$page = new page(__FILE__, 'Add a product');
$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui grid">

            <div class="eleven wide column">
                <div class="ui segment">
                    <form class="ui form" method="post">
                        <div class="field">
                            <label>URL</label>
                            <input type="url" name="url" />
                        </div>

                        <div class="field">
                            <label>Wishlist</label>
                            <select class="ui search selection dropdown loading wishlists" name="wishlist_id">
                                <option value="">Loading your wishlists...</option>
                            </select>
                        </div>

                        <input class="ui primary button" type="submit" value="Add" />
                    </form>
                </div>
            </div>

            <div class="five wide column">
                <div class="ui fitted segment">

                    <div class="ui fluid card">
                        <div class="image">
                            <img class="preview" src="/src/assets/img/no-image.svg" loading="lazy" />

                            <button class="ui icon button refresh">
                                <i class="refresh icon"></i>
                            </button>
                        </div>
                        <div class="content">
                            <div class="header">
                                <a>Title</a>
                            </div>
                        </div>
                        <div class="extra content buttons">
                            <a class="ui small right labeled icon button disabled" target="_blank">
                                <i class="external icon"></i>
                                View
                            </a>

                            <a class="ui small labeled red icon button disabled">
                                <i class="trash icon"></i>
                                Delete
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

<?php
$page->footer();
