<?php

/**
 * getWishlistNameSuggestion
 *
 * Returns the current season
 */

function getWishlistNameSuggestion(): string
{
    global $user;

    $now   = time();
    $month = date('n');
    $name  = '';

    $startOfBirthdate = null;
    $startOfEaster    = strtotime('15. April'); // Approximate
    $startOfChristmas = strtotime('24. December');

    if ($user->birthdate) {
        $birthdates = explode('-', $user->birthdate);

        $birthdate = new \DateTime();
        $birthdate->setDate(date('Y'), $birthdates[1], $birthdates[2]);

        $startOfBirthdate = $birthdate->getTimestamp();
    }

    if ($startOfBirthdate && $now <= $startOfBirthdate) {
        $name = 'Birthday';
    } elseif ($now <= $startOfEaster) {
        $name = 'Easter';
    } elseif ($now <= $startOfChristmas) {
        $name = 'Christmas';
    }

    return $name;
}
