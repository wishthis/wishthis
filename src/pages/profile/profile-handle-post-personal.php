<?php

/**
 * The "Personal" section of the users profile.
 */

namespace wishthis;

/**
 * Name (First)
 */
if (isset($_POST['user-name-first'])) {
    $nameFirst = Sanitiser::sanitiseText($_POST['user-name-first']);

    if ($nameFirst !== $user->getNameFirst()) {
        $database->query(
            'UPDATE `users`
                SET `name_first` = :name_first
              WHERE `id` = :user_id',
            array(
                'name_first' => $nameFirst,
                'user_id'    => $userId,
            )
        );

        $user->setNameFirst($nameFirst);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users first name. */
                __('First name updated to "%s".'),
                '<strong>' . $nameFirst . '</strong>'
            ),
            __('Success')
        );
    }
}

/**
 * Name (Last)
 */
if (isset($_POST['user-name-last'])) {
    $nameLast = Sanitiser::sanitiseText($_POST['user-name-last']);

    if ($nameLast !== $user->getNameLast()) {
        $database->query(
            'UPDATE `users`
                SET `name_last` = :name_last
              WHERE `id` = :user_id',
            array(
                'name_last' => $nameLast,
                'user_id'   => $userId,
            )
        );

        $user->setNameLast($nameLast);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users last name. */
                __('Last name updated to "%s".'),
                '<strong>' . $nameLast . '</strong>'
            ),
            __('Success')
        );
    }
}

/**
 * Name (Nick)
 */
if (isset($_POST['user-name-nick'])) {
    $nameNick = Sanitiser::sanitiseText($_POST['user-name-nick']);

    if ($nameNick !== $user->getNameNick()) {
        $database->query(
            'UPDATE `users`
                SET `name_nick` = :name_nick
              WHERE `id` = :user_id',
            array(
                'name_nick' => $nameNick,
                'user_id'   => $userId,
            )
        );

        $user->setNameNick($nameNick);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users nick name. */
                __('Nick name updated to "%s".'),
                '<strong>' . $nameNick . '</strong>'
            ),
            __('Success')
        );
    }
}

/**
 * Email
 */
if (isset($_POST['user-email'])) {
    $email = Sanitiser::sanitiseEmail($_POST['user-email']);

    if ($email !== $user->getEmail()) {
        $database->query(
            'UPDATE `users`
                SET `email` = :email
              WHERE `id` = :user_id',
            array(
                'email'   => $email,
                'user_id' => $userId,
            )
        );

        $user->setEmail($email);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users email address. */
                __('Email address updated to "%s".'),
                '<strong>' . $email . '</strong>'
            ),
            __('Success')
        );
    }
}

/**
 * Birthdate
 */
if (isset($_POST['user-birthdate'])) {
    $birthdateTimestamp = \strtotime($_POST['user-birthdate']);

    if (\is_int($birthdateTimestamp)) {
        $birthdate = \date('Y-m-d', $birthdateTimestamp);

        if ($birthdate !== $user->getBirthdate()) {
            $database->query(
                'UPDATE `users`
                    SET `birthdate` = :birthdate
                  WHERE `id` = :user_id',
                array(
                    'birthdate' => $birthdate,
                    'user_id'   => $userId,
                )
            );

            $user->setBirthdate($birthdate);
        }
    }
}
