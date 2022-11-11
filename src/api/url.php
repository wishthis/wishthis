<?php

/**
 * URL
 *
 * @category API
 */

namespace wishthis;

global $page;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['url'])) {
            $url_old = base64_decode($_GET['url']);
            $url     = new URL($url_old);

            $response['data'] = array(
                'url'           => $url_old,
                'url_pretty'    => $url->getPretty(),
                'url_is_pretty' => $url->isPretty(),
            );
        }
        break;
}
