import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '192.168.59.131', // your LAN IP
        port: 5173,
        hmr: { host: '192.168.59.131' } // Hot Module Reloading also uses your IP
        // hmr: false
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/chat.js' // add chat.js here
            ],
            refresh: true,
        }),
    ],
});


