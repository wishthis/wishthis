<?php

/**
 * Various statistics
 *
 * @category API
 */

namespace wishthis;

global $page, $database;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['table'])) {
            if ('all' === $_GET['table']) {
                $tables = array(
                    'wishes',
                    'wishlists',
                    'users',
                );

                $response['data'] = array();

                foreach ($tables as $table) {
                    /** Get count */
                    $countQuery = new Cache\Query(
                        'SELECT COUNT(`id`) AS "count"
                           FROM `' . $table . '`;',
                        array(),
                        Duration::DAY
                    );

                    $count                    = $countQuery->get();
                    $response['data'][$table] = $count;

                    /** Get last modified */
                    $user_time_zome = new \IntlDateFormatter(
                        $_SESSION['user']->getLocale()
                    );
                    $user_time_zome = $user_time_zome->getTimeZoneId();

                    $datetimeFormatter            = new \IntlDateFormatter(
                        $_SESSION['user']->getLocale(),
                        \IntlDateFormatter::RELATIVE_FULL,
                        \IntlDateFormatter::SHORT,
                        $user_time_zome
                    );
                    $response['data']['modified'] = $datetimeFormatter->format($countQuery->getLastModified());
                }
            } else {
                $table = Sanitiser::getTable($_GET['table']);

                $count = $database
                ->query(
                    'SELECT COUNT(`id`) AS "count"
                       FROM `' . $table . '`;'
                )
                ->fetch();

                $response['data'] = $count;
            }
        }
        break;
}
