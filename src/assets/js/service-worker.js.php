<?php

/**
 * Service Worker
 */

namespace wishthis;

$assets = array();

$css   = array_map(
    function ($stylesheet) {
        return '/src/assets/css/' . $stylesheet;
    },
    scandir(ROOT . '/src/assets/css')
);
$css[] = '/src/assets/css/default/dark.css';

$img = array_merge(
    array_map(
        function ($image) {
            return '/src/assets/img/' . $image;
        },
        scandir(ROOT . '/src/assets/img')
    ),
    array_map(
        function ($favicon) {
            return '/src/assets/img/favicon/' . $favicon;
        },
        scandir(ROOT . '/src/assets/img/favicon')
    )
);

$js = array_merge(
    array_map(
        function ($script) {
            return '/src/assets/js/' . $script;
        },
        scandir(ROOT . '/src/assets/js')
    ),
    array_map(
        function ($script) {
            return '/src/assets/js/parts/' . $script;
        },
        scandir(ROOT . '/src/assets/js/parts')
    )
);

$assets = array_merge($css, $img, $js);

ob_start();
?>

const service_worker = 'wishthis';
const assets         = [
    <?php
    foreach ($assets as $asset) {
        $pathinfo = pathinfo($asset);

        if (!in_array($pathinfo['basename'], array('.', '..'), true) && isset($pathinfo['extension']) && 'php' !== $pathinfo['extension']) {
            echo '\'' . $asset . '\',' . "\n    ";
        }
    }
    ?>
];

self.addEventListener('install', installEvent => {
    installEvent.waitUntil(
        caches.open(service_worker).then(cache => {
            cache.addAll(assets);
        })
    )
})

self.addEventListener('fetch', fetchEvent => {
    fetchEvent.respondWith(
        caches.match(fetchEvent.request).then(res => {
            return res || fetch(fetchEvent.request);
        })
    )
})

<?php
file_put_contents(ROOT . '/service-worker.js', ob_get_clean());
