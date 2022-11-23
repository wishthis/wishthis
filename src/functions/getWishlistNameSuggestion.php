<?php

/**
 * getWishlistNameSuggestion
 *
 * Returns the current season
 */

namespace wishthis;

function getWishlistNameSuggestion(): string
{
    $user  = isset($_SESSION['user']->id) ? $_SESSION['user'] : new User();
    $now   = time();
    $month = date('n');
    $name  = '';

    $startOfBirthdate = null;
    $startOfEaster    = strtotime('15. April'); // Approximate
    $startOfChristmas = strtotime('24. December');

    if (isset($user->birthdate)) {
        $birthdates = explode('-', $user->birthdate);

        $birthdate = new \DateTime();
        $birthdate->setDate(date('Y'), $birthdates[1], $birthdates[2]);

        $startOfBirthdate = $birthdate->getTimestamp();
    }

    if ($startOfBirthdate && $now <= $startOfBirthdate) {
        $name = __('Birthday');
    } elseif ($now <= $startOfEaster) {
        $name = __('Easter');
    } elseif ($now <= $startOfChristmas) {
        $name = __('Christmas');
    }

    return $name;
}
