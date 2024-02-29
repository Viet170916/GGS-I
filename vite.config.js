import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: true,
        port: 8000,
        hmr: {
            clientPort: 5173,
            host: 'localhost'
        },
        watch: {
            usePolling: true
        },
    }
});
