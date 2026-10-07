import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                serif: ['Fraunces', 'Georgia', 'serif'],
            },
            screens: {
                'xs': '400px',
                ...defaultTheme.screens,
            },
            colors: {
                'autumn-bg':      '#F7F1E5',
                'autumn-bg-2':    '#EFE6D3',
                'autumn-card':    '#FFFDF8',

                'autumn-ink':     '#2B1F12',
                'autumn-muted':   '#6B5844',
                'autumn-border':  '#E5D9C3',

                'autumn-green':   '#3F5537',
                'autumn-green-d': '#2E4128',
                'autumn-green-l': '#7A8C6A',

                'autumn-gold':    '#C9942C',
                'autumn-gold-l':  '#E8C67D',
                'autumn-honey':   '#A87A2E',

                'autumn-brown':   '#8B5E34',

                'autumn-red':     '#A54A3C',
                'autumn-success': '#5E7A4A',
            },
            boxShadow: {
                'warm':    '0 4px 16px -4px rgba(139, 94, 52, 0.15)',
                'warm-lg': '0 8px 28px -6px rgba(139, 94, 52, 0.22)',
            },
        },
    },

    plugins: [forms],
};