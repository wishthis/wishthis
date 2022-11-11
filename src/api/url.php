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
            $url = new URL(base64_decode($_GET['url']));

            $response['data'] = array(
                'url'            => $url->getPretty(),
                'url_old'        => $url->url,
                'url_old_pretty' => $url->isPretty(),
            );
        }
        break;
}
