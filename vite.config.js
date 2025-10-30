import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin';
import tailwindcss from '@tailwindcss/vite';
import scrollbarHide from 'tailwind-scrollbar-hide';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss({
            config: {
                content: ['./resources/**/*.blade.php'],
                plugins: [scrollbarHide],
            },
        }),
        livewire(), // 👈 enables livewire hot reload
    ],
    server: {
        cors: true,
    },
});
