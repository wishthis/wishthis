<?php

/**
 * URL
 *
 * @category API
 */

namespace wishthis;

$api      = true;
$response = array();

ob_start();

require '../../index.php';

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

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
