<?php

/**
 * url.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\URL;

$api      = true;
$response = array();

ob_start();

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['url'])) {
            $url = new URL(base64_decode($_GET['url']));

            $response['data'] = array(
                'url' => $url->getPretty()
            );
        }
        break;
}

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
