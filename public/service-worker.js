self.addEventListener("install", () => {
    self.skipWaiting();
});

// Do NOT override fetch unless absolutely needed
// self.addEventListener("fetch", () => {});

self.addEventListener("push", (event) => {
    if (!event.data) {
        console.warn("Push event with no data.");
        return;
    }

    let eventData = {};
    try {
        eventData = event.data.json();
    } catch (e) {
        console.error("Push JSON parse error:", e);
        return;
    }

    const options = {
        body: eventData.body || "",
        icon: "/icons/quick.png",
        silent: false,
        vibrate: [200, 100, 200],
        data: {
            url: eventData?.data?.url || "/",
        },
        requireInteraction: true,
    };

    event.waitUntil(
        (async () => {
            await self.registration.showNotification(
                eventData.title || "Alert",
                options
            );

            if (eventData.data?.soundAlert) {
                try {
                    const clientsList = await self.clients.matchAll({
                        includeUncontrolled: true,
                    });
                    for (const client of clientsList) {
                        client.postMessage({
                            type: "PLAY_SOUND",
                            sound: `/sounds/${eventData.data.soundType}.mp3`,
                        });
                    }
                } catch (e) {
                    console.warn("Client message failed:", e);
                }
            }
        })()
    );
});

self.addEventListener("notificationclick", (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || "/";

    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((list) => {
                for (const client of list) {
                    if (client.url === targetUrl && "focus" in client) {
                        return client.focus();
                    }
                }
                return clients.openWindow(targetUrl);
            })
    );
});
