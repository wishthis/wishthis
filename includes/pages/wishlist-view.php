<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};
use Embed\Embed;

$page = new page(__FILE__, 'View wishlist');
$page->header();
$page->navigation();

$products = array();

/**
 * Get wishlist products
 */
if (isset($_GET['wishlist'])) {
    $user = new User();
    $wishlist = $_GET['wishlist'];
    $products = $user->getProducts($wishlist);
}

/**
 * Delete wishlist
 */
if (isset($_POST['wishlist_delete_id'])) {
    $database->query('DELETE FROM `wishlists`
        WHERE id = ' . $_POST['wishlist_delete_id'] . '
    ;');
}
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui horizontal segments">
            <div class="ui segment">
                <h2 class="ui header">Wishlists</h2>
                <p>Please select a wishlist to view.</p>

                <form class="ui form" method="get">
                    <input type="hidden" name="page" value="wishlist-view" />

                    <div class="field">
                        <select class="ui search selection dropdown loading wishlists" name="wishlist">
                            <option value="">Loading your wishlists...</option>
                        </select>
                    </div>

                    <input class="ui primary button wishlist-view disabled" type="submit" value="View" />
                </form>
            </div>

            <div class="ui segment">
                <h2 class="ui header">Options</h1>
                <p>Wishlist related options.</p>

                <button class="ui small labeled icon button disabled">
                    <i class="share icon"></i>
                    Share
                </button>

                <form class="ui form wishlist-delete" method="post" style="display: inline-block;">
                    <input type="hidden" name="wishlist_delete_id" />

                    <button class="ui small labeled red icon button disabled" type="submit">
                        <i class="trash icon"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <?php if (!empty($products)) { ?>
            <div class="ui three column grid">

                <?php foreach ($products as $product) { ?>
                    <?php
                    /**
                     * @link https://github.com/oscarotero/Embed
                     */
                    $embed = new Embed();
                    $info  = $embed->get($product['url']);
                    ?>
                        <div class="column">
                            <div class="ui fluid card">

                                <?php if ($info->image) { ?>
                                    <div class="image">
                                        <img src="<?= $info->image ?>" />
                                    </div>
                                <?php } ?>

                                <div class="content">
                                    <?php if ($info->title) { ?>
                                        <div class="header">
                                            <?php if ($info->url) { ?>
                                                <a href="<?= $info->url ?>" target="_blank"><?= $info->title ?></a>
                                            <?php } else { ?>
                                                <?= $info->title ?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <?php if ($info->keywords) { ?>
                                        <div class="meta">
                                            <?= $info->keywords ?>
                                        </div>
                                    <?php } ?>

                                    <?php if ($info->description) { ?>
                                        <div class="description">
                                            <?= $info->description ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="extra content">
                                    <?php if ($info->publishedTime) { ?>
                                        <span class="right floated">
                                            <?= $info->publishedTime ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ($info->favicon) { ?>
                                        <?php if ($info->providerName) { ?>
                                            <img src="<?= $info->favicon ?>"
                                                 title="<?= $info->providerName ?>"
                                                 alt="<?= $info->providerName ?>"
                                            />
                                        <?php } else { ?>
                                            <img src="<?= $info->favicon ?>" />
                                        <?php } ?>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                <?php } ?>

            </div>
        <?php } else { ?>
            <?php if (isset($_GET['wishlist'])) { ?>
                <div class="ui icon message">
                    <i class="info circle icon"></i>
                    <div class="content">
                        <div class="header">
                            Empty
                        </div>
                        <p>The selected wishlist seems to be empty.</p>
                        <a class="ui mini button" href="/?page=wishlist-product-add">Add a product</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="ui icon message">
                    <i class="info circle icon"></i>
                    <div class="content">
                        <div class="header">
                            No wishlist selected
                        </div>
                        <p>Select a wishlist to see it's products.</p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</main>

<?php
$page->footer();
