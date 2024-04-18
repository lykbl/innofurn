import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/apollo-client.js',
                'resources/js/main.js',
            ],
            refresh: true,
            postcss: [
                // tailwindcss(),
            ],
            paths: [
              'resources/css/**/*.css',
              'resources/js/**/*.js',
              'resources/views/**/*.js',
            ],
        }),
    ],
});
