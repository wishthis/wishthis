<?php

/**
 * Save preview
 *
 * @category API
 */

namespace wishthis;

global $page;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

$user = User::getCurrent();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_POST['preview'], $_POST['page'])) {
            $preview          = substr($_POST['preview'], 22); // data:image/png;base64,
            $preview          = base64_decode($preview);
            $preview_filepath = ROOT . '/src/assets/img/screenshots/' . $_POST['page'] . '.png';
            $preview_create   = false;

            $page_filepath = ROOT . 'src/pages/' . $_POST['page'] . '.php';
            $page          = new Page($page_filepath);

            if (file_exists($preview_filepath)) {
                $preview_age = time() - filemtime($preview_filepath);

                if ($preview_age > Duration::MONTH) {
                    $preview_create = true;
                }
            } else {
                $preview_create = true;
            }

            if ($preview_create && $user->getPower() >= $page->power) {
                file_put_contents($preview_filepath, $preview);
            }
        }
        break;
}
