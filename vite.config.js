import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/styles.css', // Incluye tu archivo styles.css aquí
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
