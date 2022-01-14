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
        <div class="ui segment">
            <h1 class="ui header">Welcome to wishthis</h1>
            <p>
                wishthis is a simple, intuitive and modern plattform to create,
                manage and view your wishes for any kind of occasion.
            </p>
        </div>
    </div>
</main>

<?php
$page->footer();
?>
