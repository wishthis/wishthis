<?php

/**
 * The settings page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Settings'), 100);
$page->header();
$page->bodyStart();
$page->navigation();

if (isset($_POST['mjml_api'], $_POST['api_application_id'], $_POST['mjml_api_secret_key'])) {
    $options->setOption('mjml_api_application_id', $_POST['api_application_id']);
    $options->setOption('mjml_api_secret_key', $_POST['mjml_api_secret_key']);
}
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('MJML') ?></h2>
            <p><?= sprintf(__('MJML is required for sending emails. Visit %s to request API access.'), '<a href="https://mjml.io/api" target="_blank">https://mjml.io/api</a>') ?></p>

            <h3 class="ui header"><?= __('API') ?></h3>
            <form class="ui form" method="POST">
                <div class="field">
                    <label><?= __('Application ID') ?></label>
                    <input type="text"
                           name="api_application_id"
                           placeholder="01234567-89ab-cdef-0123-456789abcdef"
                           value="<?= $options->getOption('mjml_api_application_id'); ?>"
                    />
                </div>

                <div class="field">
                    <label><?= __('Secret Key') ?></label>
                    <input type="text"
                           name="mjml_api_secret_key"
                           placeholder="01234567-89ab-cdef-0123-456789abcdef"
                           value="<?= $options->getOption('mjml_api_secret_key'); ?>"
                    />
                </div>

                <input class="ui primary button"
                       type="submit"
                       name="mjml_api"
                       value="<?= __('Save') ?>"
                       title="<?= __('Save') ?>"
                />
            </form>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
