<?php

namespace wishthis;

class PageControllerMaintenance extends PageController
{
    protected string $id                   = 'maintenance';
    protected bool $requiresAuthentication = false;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Maintenance');

        parent::__construct();
    }

    public function default(): void
    {
        global $options;

        $versionCurrent    = $options->version;
        $versionNew        = VERSION;
        $versionIsOutdated = \version_compare($versionCurrent, $versionNew, '<');

        if (!$versionIsOutdated) {
            \redirect('/');
        }

        $this->placeholders['MAINTENANCE_HEADING']       = __('Temporarily unavailable');
        $this->placeholders['MAINTENANCE_DESCRIPTION_1'] = __('Due to maintenance, wishthis is temporarily not available. Please check back again in a minute.');
        $this->placeholders['MAINTENANCE_DESCRIPTION_2'] = __('If you are the administrator of this site, please log in.');

        parent::render();
    }
}
