import "./bootstrap";
import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Axios = axios;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: false,
});

// // ✅ Step 1: Get Sanctum CSRF cookie first
// axios.get('/sanctum/csrf-cookie').then(() => {

//     // ✅ Step 2: After cookie is set, initialize Echo
//     window.Echo = new Echo({
//         broadcaster: 'pusher',
//         key: import.meta.env.VITE_PUSHER_APP_KEY,
//         cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//         forceTLS: false,
//         wsHost: window.location.hostname,
//         wsPort: 6001,     // if using laravel-websockets
//         wssPort: 6001,
//         disableStats: true,
//         enabledTransports: ['ws', 'wss'],
//         withCredentials: true, // ✅ ensures cookies are sent
//     });

//     // ✅ Step 3: Subscribe to your private channel (for the current user)
//     window.Echo.private(`user.${window.userId}`)
//         .listen('.NewNotification', (e) => {
//             console.log('🔔 New Notification Received:', e);

//             // ✅ Only play purge.mp3 for reporter or designated personnel notifications (BFP acceptance)
//             if (e.role === 'reporter' || e.role === 'designated' || e.role === 'rescue' || e.role === 'pnp') {
//                 const audio = new Audio('/purge.mp3'); // Assuming purge.mp3 is in the public directory
//                 audio.play().catch(error => {
//                     console.error('Error playing audio:', error);
//                     // Fallback: Show a visual notification
//                     alert('A report has been accepted by BFP!');
//                 });
//             }

//             // Optional: Update UI for all notifications (e.g., refresh dashboard or show toast)
//             // Example: If using a library like Toastr
//             // toastr.info(e.notification.message);
//         });
// });
