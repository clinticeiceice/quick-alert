self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open("quick-alert-cache").then((cache) => {
            return cache.addAll([
                "/",
                "/manifest.json",
                // add more routes/files if needed
            ]);
        })
    );
});

self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return fetch(event.request);
        })
    );
});

self.addEventListener("notificationclick", function (event) {
    event.notification.close(); // Close the notification

    const targetUrl = event.notification.data
        ? event.notification.data.url
        : "/"; // Get URL from notification data or default

    event.waitUntil(
        clients.matchAll({ type: "window" }).then(function (clientList) {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === targetUrl && "focus" in client) {
                    return client.focus(); // Focus existing window
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl); // Open new window
            }
        })
    );
});

// Inside your service worker's 'push' event listener
self.addEventListener("push", function (event) {
    console.log(event.data?.json());
    const eventData = event.data?.json();

    const options = {
        body: eventData?.body,
        icon: "/icons/quick.png",
        silent: false,
        vibrate: [200, 100, 200], // Accompanying vibration for mobile
        data: {
            url: eventData?.data?.url, // URL to open on click
        },
    };

    event.waitUntil(
        // Chain the audio playback and notification
        (async () => {
            // First, show the notification
            await self.registration.showNotification(eventData?.title, options);

            if (eventData?.data?.soundAlert) {
                // Send a message to all open clients (tabs) to play a sound
                const clientsList = await self.clients.matchAll({
                    includeUncontrolled: true,
                });

                for (const client of clientsList) {
                    client.postMessage({
                        type: "PLAY_SOUND",
                        sound: "/sounds/purge.mp3",
                    });
                }
            }
        })()
    );
});
