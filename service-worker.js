const service_worker = 'wishthis-v1.1.0';
const assets         = [
    '/src/assets/css/blog.css',
    '/src/assets/css/default.css',
    '/src/assets/css/home.css',
    '/src/assets/css/install.css',
    '/src/assets/css/post.css',
    '/src/assets/css/wish-card.css',
    '/src/assets/css/wish.css',
    '/src/assets/css/wishlists-saved.css',
    '/src/assets/css/default/dark.css',
    '/src/assets/img/favicon.png',
    '/src/assets/img/favicon.svg',
    '/src/assets/img/logo-animation.svg',
    '/src/assets/img/logo-readme.svg',
    '/src/assets/img/logo-square-white-background.png',
    '/src/assets/img/logo-square-white-background.svg',
    '/src/assets/img/logo-square.png',
    '/src/assets/img/logo-square.svg',
    '/src/assets/img/logo.svg',
    '/src/assets/img/no-image.svg',
    '/src/assets/img/favicon/android-chrome-192x192.png',
    '/src/assets/img/favicon/android-chrome-512x512.png',
    '/src/assets/img/favicon/apple-touch-icon.png',
    '/src/assets/img/favicon/browserconfig.xml',
    '/src/assets/img/favicon/favicon-16x16.png',
    '/src/assets/img/favicon/favicon-32x32.png',
    '/src/assets/img/favicon/favicon.ico',
    '/src/assets/img/favicon/mstile-144x144.png',
    '/src/assets/img/favicon/mstile-150x150.png',
    '/src/assets/img/favicon/mstile-310x150.png',
    '/src/assets/img/favicon/mstile-310x310.png',
    '/src/assets/img/favicon/mstile-70x70.png',
    '/src/assets/img/favicon/safari-pinned-tab.svg',
    '/src/assets/js/changelog.js',
    '/src/assets/js/default.js',
    '/src/assets/js/home.js',
    '/src/assets/js/html2canvas.js',
    '/src/assets/js/install.js',
    '/src/assets/js/login-as.js',
    '/src/assets/js/login.js',
    '/src/assets/js/profile.js',
    '/src/assets/js/register.js',
    '/src/assets/js/wishlist.js',
    '/src/assets/js/parts/wish.js',
    '/src/assets/js/parts/wishlist-filter-priority.js',
    '/src/assets/js/parts/wishlists.js',
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
        caches.open(service_worker).then(cache => {
            return cache.match(fetchEvent.request).then(response => {
                const fetchPromise = fetch(fetchEvent.request).then(networkResponse => {
                    cache.put(fetchEvent.request, networkResponse.clone());
                    return networkResponse;
                });
                return response || fetchPromise;
            });
        })
    );
});
