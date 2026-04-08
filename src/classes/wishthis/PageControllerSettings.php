<?php

namespace wishthis;

class PageControllerSettings extends PageController
{
    protected string $id = 'settings';

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Settings');

        parent::__construct();
    }

    public function default(): void
    {
        global $options;

        $this->placeholders['MJML_HEADING']            = __('MJML');
        $this->placeholders['MJML_DESCRIPTION']        = \sprintf(
            __('MJML is required for sending emails. Visit %1$s to request API access.'),
            '<a href="https://mjml.io/api" target="_blank">mjml.io/api</a>'
        );
        $this->placeholders['MJML_API_HEADING']        = __('API');
        $this->placeholders['MJML_API_ID_HEADING']     = __('Application ID');
        $this->placeholders['MJML_API_ID']             = $options->getOption('mjml_api_application_id');
        $this->placeholders['MJML_API_SECRET_HEADING'] = __('Secret Key');
        $this->placeholders['MJML_API_SECRET']         = $options->getOption('mjml_api_secret_key');
        $this->placeholders['MJML_API_SAVE']           = __('Save');

        parent::render();
    }

    public function save(): void
    {
        global $options;

        if (
            isset(
                $_POST['mjml_api'],
                $_POST['api_application_id'],
                $_POST['mjml_api_secret_key']
            )
        ) {
            $options->setOption(
                'mjml_api_application_id',
                $_POST['api_application_id']
            );
            $options->setOption(
                'mjml_api_secret_key',
                $_POST['mjml_api_secret_key']
            );
        }

        \header('Location: /settings');
        die();
    }
}
