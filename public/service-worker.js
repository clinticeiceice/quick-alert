self.addEventListener('install', event => {
    event.waitUntil(
        caches.open('quick-alert-cache').then(cache => {
            return cache.addAll([
                '/',
                '/manifest.json'
                // add more routes/files if needed
            ]);
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});
