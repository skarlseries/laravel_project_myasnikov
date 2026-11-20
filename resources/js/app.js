import { createApp } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import App from './components/App.vue';

// Pusher используется Laravel Echo в качестве WebSocket-клиента.
window.Pusher = Pusher;

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

if (pusherKey && pusherCluster) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: pusherCluster,
        forceTLS: true,
    });
} else {
    console.warn(
        'Pusher не настроен. Проверьте PUSHER_APP_KEY и PUSHER_APP_CLUSTER в файле .env.'
    );
}

// Монтируем Vue-компонент, если корневой элемент есть в Blade-шаблоне.
const root = document.getElementById('app');

if (root) {
    createApp(App).mount(root);
}
