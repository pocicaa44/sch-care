import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws'],
});

// Hanya untuk siswa (private channel)
const userIdElement = document.body.getAttribute('data-user-id') ||
                      document.querySelector('meta[name="user-id"]')?.getAttribute('content');
if (userIdElement) {
    const userId = parseInt(userIdElement);
    window.Echo.private(`user.${userId}`)
        .listen('.status-updated', (e) => {
            console.log('Status berubah:', e.report);
            // Update badge status card siswa
            const card = document.querySelector(`#report-card-${e.report.id} .badge-status`);
            if (card) {
                card.className = `badge-status badge-${e.report.status}`;
                card.textContent = e.report.status;
            }
        });
}