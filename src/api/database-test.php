<?php

/**
 * Database test
 *
 * @category API
 */

namespace wishthis;

ob_start();

$api = true;

require '../../index.php';

$response = array();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $success = false;

        try {
            $dsn = 'mysql:host=' . $_POST['DATABASE_HOST'] . ';dbname=' . $_POST['DATABASE_NAME'] . ';port=3306;charset=utf8';
            $pdo = new \PDO(
                $dsn,
                $_POST['DATABASE_USER'],
                $_POST['DATABASE_PASSWORD']
            );

            $success = true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        $response['success'] = $success;
        break;
}

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
