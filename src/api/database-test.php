<?php

/**
 * Database test
 *
 * @category API
 */

namespace wishthis;

global $page, $database;

if (isset($database) && $database) {
    echo __('Refused to test database connection after installation.');

    $response['dbTestSuccess'] = false;

    return;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $success = false;

        try {
            $dbTest = new Database(
                engine:   $_POST['DATABASE_ENGINE'],
                host:     $_POST['DATABASE_HOST'],
                database: $_POST['DATABASE_NAME'],
                user:     $_POST['DATABASE_USER'],
                password: $_POST['DATABASE_PASSWORD'],
            );
            $dbTest->connect();

            $success = true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        $response['dbTestSuccess'] = $success;
        break;
}
