<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, Database};

$page = new page(__FILE__, 'Home');
$page->header();

$step = isset($_POST['step']) ? $_POST['step'] : 1;

switch ($step) {
    case 1:
        ?>
        <main>
            <section>
                <h1>Install</h1>
                <h2>Step <? $step ?></h2>
                <p>Welcome to the wishthis installer.</p>
                <p>wishthis needs a database to function properly. Please enter your credentials.</p>

                <form method="post">
                    <input type="hidden" name="action" value="install" />
                    <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                    <fieldset>
                        <label>Host</label>
                        <input type="text" name="DATABASE_HOST" placeholder="localhost" value="localhost" />
                    </fieldset>

                    <fieldset>
                        <label>Name</label>
                        <input type="text" name="DATABASE_NAME" placeholder="withthis" value="withthis" />
                    </fieldset>

                    <fieldset>
                        <label>Username</label>
                        <input type="text" name="DATABASE_USER" placeholder="root" value="root" />
                    </fieldset>

                    <fieldset>
                        <label>Password</label>
                        <input type="text" name="DATABASE_PASSWORD" />
                    </fieldset>

                    <input type="submit" value="Install" />
                </form>
            </section>
        </main>
        <?php
        break;

    case 2:
        $configDirectory = 'includes/config';
        $configPath = $configDirectory . '/config.php';
        $configSamplePath = $configDirectory . '/config-sample.php';
        $configContents = file_get_contents($configSamplePath);

        foreach ($_POST as $key => $value) {
            if ('DATABASE' === substr($key, 0, 8)) {
                $configContents = preg_replace('/(' . $key . '.+?\').*?(\')/', '$1' . $value . '$2', $configContents);
            }
        }

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
        break;
}

$page->footer();
