<?php

/**
 * Database test
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
