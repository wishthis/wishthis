<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;
use Embed\Embed;

if (isset($_POST['url'], $_POST['wishlist'])) {
    $database->query('INSERT INTO `products`
        (`wishlist`, `url`) VALUES
        (' . $_POST['wishlist'] . ', "' . $_POST['url'] . '")
    ;');
}

$url = 'https://www.amazon.de/Adventskalender-2020-Schokolade-Weihnachtskalender-346g/dp/B08CTTP5JX';
$embed = new Embed();
$info = $embed->get($url);

// https://github.com/oscarotero/Embed
// echo '<pre>';
// var_dump($info);
// echo '</pre>';

$page = new page(__FILE__, 'Add a product');
$page->header();
$page->navigation();
?>
<main>
<div class="ui container">
    <div class="ui segment">
        <h1 class="ui header">Add a product</h1>

        <form class="ui form" method="post">
            <div class="field">
                <label>URL</label>
                <input type="url" name="url" value="<?= $url ?>" />
            </div>

            <div class="field">
                <label>Wishlist</label>
                <input type="number" name="wishlist" value="1" />
            </div>

            <input class="ui primary button" type="submit" value="Add" />
        </form>
    </div>
</div>
</main>

<?php
$page->footer();
