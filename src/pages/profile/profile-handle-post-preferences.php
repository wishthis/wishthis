<?php

/**
 * The "Preferences" section of the users profile.
 */

namespace wishthis;

/**
 * Language
 */
if (isset($_POST['user-language'])) {
    $userLocale = $_POST['user-language'];

    /**
     * To do
     *
     * Verify the submitted locale actually exists.
     */
    /** */

    if ($userLocale !== $user->getLocale()) {
        $database->query(
            'UPDATE `users`
                SET `language` = :language
              WHERE `id` = :user_id',
            array(
                'language' => $userLocale,
                'user_id'  => $userId,
            )
        );

        $user->setLocale($userLocale);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users locale. */
                __('Locale updated to "%s".'),
                '<strong>' . $userLocale . '</strong>'
            ),
            __('Success')
        );
    }
}

/**
 * Currency
 */
if (isset($_POST['user-currency'])) {
    $userCurrency = $_POST['user-currency'];

    /**
     * To do
     *
     * Verify the submitted currency actually exists.
     */
    /** */

    if ($userCurrency !== $user->getCurrency()) {
        $database->query(
            'UPDATE `users`
                SET `currency` = :currency
              WHERE `id` = :user_id',
            array(
                'currency' => $userCurrency,
                'user_id'  => $userId,
            )
        );

        $user->setCurrency($userCurrency);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users currency. */
                __('Currency updated to "%s".'),
                '<strong>' . $userCurrency . '</strong>'
            ),
            __('Success')
        );
    }
}

/**
 * Channel
 */
if (isset($_POST['user-channel'])) {
    $userChannel = $_POST['user-channel'];

    $channels = \defined('CHANNELS') ? \array_map(
        function ($channel) {
            return $channel['branch'] ?? '';
        },
        CHANNELS
    )
    : array();

    if (\in_array($userChannel, $channels, true) && $userChannel !== $user->getChannel()) {
        $database->query(
            'UPDATE `users`
                SET `channel` = :channel
              WHERE `id` = :user_id',
            array(
                'channel' => $userChannel,
                'user_id' => $userId,
            )
        );

        $user->setChannel($userChannel);

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The users channel. */
                __('Channel updated to "%s".'),
                '<strong>' . $userChannel . '</strong>'
            ),
            __('Success')
        );
    }

    if ('' === $userChannel && '' !== $user->getChannel()) {
        $database->query(
            'UPDATE `users`
                SET `channel` = :channel
              WHERE `id` = :user_id',
            array(
                'channel' => null,
                'user_id' => $userId,
            )
        );

        $user->setChannel($userChannel);

        $page->messages[] = Page::success(
            __('Channel has been reset.'),
            __('Success')
        );
    }
}

/**
 * Advertisements
 */
$userAdvertisements = isset($_POST['enable-advertisements']);

if ($userAdvertisements !== $user->getAdvertisements()) {
    $database->query(
        'UPDATE `users`
            SET `advertisements` = :advertisements
            WHERE `id` = :user_id',
        array(
            'advertisements' => $userAdvertisements,
            'user_id'        => $userId,
        )
    );

    $user->setAdvertisements($userAdvertisements);

    $page->messages[] = Page::success(
        sprintf(
            /** TRANSLATORS: %s: The users advertisements. */
            __('Advertisements updated to "%s".'),
            '<strong>' . $userAdvertisements ? 'True' : 'False' . '</strong>'
        ),
        __('Success')
    );
}


if ($loginRequired) {
    session_destroy();
    unset($_SESSION);

    $page->messages[] = Page::warning(
        __('Your account credentials have changed and you have been logged out. Please log in again.'),
        __('Account credentials changed')
    );
}
