<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

$page = new page(__FILE__, 'Update');
$page->header();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php if ($user->isLoggedIn()) { ?>
        <?php } else { ?>
            <div class="ui segment">
                <h2 class="ui header">Maintenance</h2>
                <p>
                    The administrator of this site is currently running an update.
                    This usually just takes a couple of seconds.
                </p>
                <p>
                    Trying again in <span id="retryIn">5</span> seconds...
                </p>
                <div class="ui primary progress nolabel" data-percent="74" id="example1">
                    <div class="bar"></div>
                </div>
            </div>
        <?php } ?>
    </div>
</main>

<?php
$page->footer();
?>
