<?php

/**
 * wishlist.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;
use Brick\Schema\SchemaReader;
use Brick\Schema\Interfaces as Schema;
use Embed\Embed;

$page = new page(__FILE__, 'Add a product');

$url = 'https://www.amazon.de/Adventskalender-2020-Schokolade-Weihnachtskalender-346g/dp/B08CTTP5JX';
$embed = new Embed();
$info = $embed->get($url);

echo '<pre>';
var_dump($info->oembed);
echo '</pre>';

$page->header();
?>
<main>
<section>
    <h1>Add a product</h1>

    <form method="post">
        <fieldset>
            <label>URL</label>
            <input type="url" name="url" />
        </fieldset>

        <input type="submit" value="Add" />
    </form>
</section>
</main>

<?php
$page->footer();
