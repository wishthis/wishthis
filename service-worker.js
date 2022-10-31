const staticDevCoffee = 'wishthis'
const assets = [
    '/src/assets/css/default/dark.css',
    '/src/assets/css/blog.css',
    '/src/assets/css/default.css',
    '/src/assets/css/home.css',
    '/src/assets/css/post.css',
    '/src/assets/css/wishlists-saved.css',
    '/src/assets/css/wishlists.css',

    '/src/assets/img/favicon/android-chrome-192x192.png',
    '/src/assets/img/favicon/android-chrome-512x512.png',
    '/src/assets/img/favicon/apple-touch-icon.png',
    '/src/assets/img/favicon/favicon-16x16.png',
    '/src/assets/img/favicon/favicon-32x32.png',
    '/src/assets/img/favicon/favicon.ico',
    '/src/assets/img/favicon/mstile-70x70.png',
    '/src/assets/img/favicon/mstile-150x150.png',
    '/src/assets/img/favicon/mstile-310x150.png',
    '/src/assets/img/favicon.png',
    '/src/assets/img/favicon.svg',
    '/src/assets/img/logo-animation.svg',
    '/src/assets/img/logo-square-white-background.png',
    '/src/assets/img/logo-square-white-background.svg',
    '/src/assets/img/logo-square.png',
    '/src/assets/img/logo-square.svg',
    '/src/assets/img/logo.svg',
    '/src/assets/img/no-image.svg',

    '/src/assets/js/parts/wishlist-filter.js',
    '/src/assets/js/changelog.js',
    '/src/assets/js/default.js',
    '/src/assets/js/home.js',
    '/src/assets/js/login-as.js',
    '/src/assets/js/profile.js',
    '/src/assets/js/register.js',
    '/src/assets/js/service-worker.js',
    '/src/assets/js/wishlist.js',
    '/src/assets/js/wishlists.js',
]

self.addEventListener('install', installEvent => {
    installEvent.waitUntil(
        caches.open(staticDevCoffee).then(cache => {
            cache.addAll(assets)
        })
    )
})

self.addEventListener('fetch', fetchEvent => {
    fetchEvent.respondWith(
        caches.match(fetchEvent.request).then(res => {
            return res || fetch(fetchEvent.request)
        })
    )
})
