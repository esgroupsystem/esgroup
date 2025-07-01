import './bootstrap';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

document.addEventListener("DOMContentLoaded", () => {
    const userId = document.querySelector("meta[name='user-id']")?.getAttribute("content");

    if (userId) {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                console.log("🔔 New Notification:", notification);
                toastr.info(notification.title || 'You have a new notification!');
            });
    }
});

