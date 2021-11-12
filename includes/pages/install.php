<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');
$page->header();

if (isset($_POST['action']) && 'install' === $_POST['action']) {
    $configDirectory = 'includes/config';
    $configPath = $configDirectory . '/config.php';
    $configSamplePath = $configDirectory . '/config-sample.php';
    $configContents = file_get_contents($configSamplePath);

    file_put_contents($configPath, $configContents);

    ?>
    <main>
        <section>
            <h1>Success</h1>
            <p>wishthis has been successfully installed.</p>

            <a class="button primary" href="">Continue</a>
        </section>
    </main>
    <?php
} else {
    ?>
    <main>
        <section>
            <h1>Install</h1>
            <p>Welcome to the wishthis installer.</p>

            <p>Click Install to begin the installation.</p>

            <form method="post">
                <input type="hidden" name="action" value="install" />

                <input type="submit" value="Install" />
            </form>
        </section>
    </main>
    <?php
}

$page->footer();
