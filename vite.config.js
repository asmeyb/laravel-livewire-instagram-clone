import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import scrollbarHide from 'tailwind-scrollbar-hide';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // Tailwind + extra plugins in one place
        tailwindcss({
            config: {
                content: [
                    './resources/**/*.blade.php',
                    './resources/**/*.js',
                    './resources/**/*.vue',
                ],
                theme: {
                    extend: {},
                },
                plugins: [scrollbarHide],
            },
        }),
    ],
    server: {
        cors: true,
    },
});
