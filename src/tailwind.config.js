import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#E6FAF5',
                    100: '#CCF5EB',
                    200: '#99EBD6',
                    600: '#00B98E',
                    700: '#00966F',
                    DEFAULT: '#00B98E',
                },
            },
        },
    },

    plugins: [forms],
};
