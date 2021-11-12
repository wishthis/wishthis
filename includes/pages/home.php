<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');
$page->header();
?>

<main>
    <section>
        <h1>Welcome to wishthis</h1>
    </section>
</main>

<?php
$page->footer();
?>
