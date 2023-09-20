<?php

namespace wishthis;

if (!isset($_POST['section'])) {
    return;
}

$loginRequired = false;
$userId        = $user->getId();

require \sprintf(__DIR__ . '/profile-handle-post-%s.php', $_POST['section']);

if ($loginRequired) {
    session_destroy();
    unset($_SESSION);

    $page->messages[] = Page::warning(
        __('Your account credentials have changed and you have been logged out. Please log in again.'),
        __('Account credentials changed')
    );
}
