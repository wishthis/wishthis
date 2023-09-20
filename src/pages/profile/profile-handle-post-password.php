<?php

/**
 * The "Password" section of the users profile.
 */

namespace wishthis;

if (
       isset($_POST['user-password'])
    && isset($_POST['user-password-repeat'])
    && \strlen($_POST['user-password']) >= 8
    && \strlen($_POST['user-password-repeat']) >= 8
    && $_POST['user-password'] === $_POST['user-password-repeat']
) {
    $password = User::passwordToHash($_POST['user-password']);

    $database->query(
        'UPDATE `users`
            SET `password` = :password
          WHERE `id` = :user_id',
        array(
            'password' => $password,
            'user_id'  => $userId,
        )
    );

    $loginRequired = true;

    $page->messages[] = Page::success(
        __('Password updated.'),
        __('Success')
    );
}
