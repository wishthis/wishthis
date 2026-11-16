<?php

namespace wishthis;

class PageControllerApiStatistics extends PageController
{
    protected string $id = 'api-statistics';

    public function __construct()
    {
        $this->pageTitle = 'API';

        parent::__construct();
    }

    public function all(): void
    {
        $tables = [
            'wishes',
            'wishlists',
            'users',
        ];

        $response['data'] = [];

        $user = User::getCurrent();

        \ob_start();

        foreach ($tables as $table) {
            /** Get count */
            $countQuery = new Cache\Query(
                'SELECT COUNT(`id`) AS "count"
                           FROM `' . $table . '`;',
                [],
                Duration::DAY
            );

            $count                    = $countQuery->get();
            $response['data'][$table] = $count;

            /** Get last modified */
            $user_time_zome = new \IntlDateFormatter(
                $user->getLocale()
            );
            $user_time_zome = $user_time_zome->getTimeZoneId();

            $datetimeFormatter            = new \IntlDateFormatter(
                $user->getLocale(),
                \IntlDateFormatter::RELATIVE_FULL,
                \IntlDateFormatter::SHORT,
                $user_time_zome
            );
            $response['data']['modified'] = $datetimeFormatter->format($countQuery->getLastModified());
        }

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }
}
