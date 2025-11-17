// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin';
import tailwindcss from '@tailwindcss/vite';

/* ----------  Tailwind “config” that used to live in tailwind.config.js  ---------- */
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';
import scrollbarHide from 'tailwind-scrollbar-hide';

const tailwindConfig = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/wire-elements/modal/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  safelist: [
    'sm:max-w-sm', 'sm:max-w-md',
    'md:max-w-lg', 'md:max-w-xl',
    'lg:max-w-2xl', 'lg:max-w-3xl',
    'xl:max-w-4xl', 'xl:max-w-5xl',
    '2xl:max-w-6xl', '2xl:max-w-7xl',
  ],

  daisyui: { themes: ['light'] },
  plugins: [forms, daisyui, scrollbarHide],
};
/* ---------------------------------------------------------------------------------- */

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),

    tailwindcss({ config: tailwindConfig }), // 👈 entire Tailwind setup inline
    livewire(),                              // Livewire hot-reload
  ],

  server: { cors: true },
});