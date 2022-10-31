<?php

/**
 * The settings page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Settings'));
$page->header();
$page->bodyStart();
$page->navigation();

if (isset($_POST['mjml_api'], $_POST['api_key'], $_POST['api_secret'])) {
    $options->setOption('mjml_api_key', $_POST['api_key']);
    $options->setOption('mjml_api_secret', $_POST['api_secret']);
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
                    <label><?= __('Key') ?></label>
                    <input type="text"
                           name="api_key"
                           placeholder="01234567-89ab-cdef-0123-456789abcdef"
                           value="<?= $options->getOption('mjml_api_key'); ?>"
                    />
                </div>

                <div class="field">
                    <label><?= __('Secret') ?></label>
                    <input type="text"
                           name="api_secret"
                           placeholder="01234567-89ab-cdef-0123-456789abcdef"
                           value="<?= $options->getOption('mjml_api_secret'); ?>"
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
