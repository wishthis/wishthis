<?php

/**
 * The "Account" section of the users profile.
 */

namespace wishthis;

/**
 * Account
 */
if (isset($_POST['account-delete'])) {
    $user->delete();
    $user->logOut();

    redirect(Page::PAGE_HOME);
}
