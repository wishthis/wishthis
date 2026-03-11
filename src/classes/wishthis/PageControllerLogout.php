<?php

namespace wishthis;

class PageControllerLogout extends PageController
{
    protected string $id = 'logout';

    public function __construct()
    {
        $this->pageTitle = __('Logout');

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $this->placeholders['LOGOUT_HEADING']     = __('Goodbye');
        $this->placeholders['LOGOUT_DESCRIPTION'] = __('You have been logged out.');

        parent::setPlaceholders();
    }

    public function default(): void
    {
        $user = User::getCurrent();
        $user->logOut();

        parent::render();
    }
}
