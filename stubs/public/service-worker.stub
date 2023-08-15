self.addEventListener('push', event => {
    const data = event.data.json();

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: data.icon,
            tag: data.tag,
            data: {
                url: data.url
            },
        })
    );
});

self.addEventListener('notificationclick', event => {

    const notification = event.notification;
    const url = notification.data.url;

    notification.close();

    event.waitUntil(
        clients.openWindow(url)
    );
});
