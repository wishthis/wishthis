<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};
use Embed\Embed;

$page = new page(__FILE__, 'Wishlist');
$page->header();
$page->navigation();

$wishlist = $database->query('SELECT * FROM `wishlists`
                                      WHERE `hash` = "' . $_GET['wishlist'] . '"')
                     ->fetch();

if ($wishlist) {
    $products = $user->getProducts($wishlist['id']);
} else {
    http_response_code(404);
    ?>
    <h1>Not found</h1>
    <p>The requested Wishlist was not found and likely deleted by its creator.</p>
    <?php
    die();
}
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php
        /**
         * Warn the wishlist creator
         */
        if (isset($user->id) && $user->id === intval($wishlist['user'])) { ?>
        <div class="ui icon warning message wishlist-own">
            <i class="exclamation triangle icon"></i>
            <div class="content">
                <div class="header">
                    Careful
                </div>
                <div class="text">
                    <p>You are viewing your own wishlist! You will be able to see which products have already been bought for you. Don't you want to be surprised?</p>
                    <p>It's probably best to just close this tab.</p>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="ui segment">
            <h2 class="ui header"><?= $wishlist['name'] ?></h2>
        </div>

        <?php if (!empty($products)) { ?>
            <div class="ui three column stackable grid wishlist-cards">

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
                        <p>This wishlist seems to be empty.</p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</main>

<?php
$page->footer();
?>
