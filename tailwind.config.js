import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*. blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend:  {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Custom green palette for our eco theme
                'eco':  {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',  // Primary green
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                boxShadow: {
                    'eco': '0 4px 14px 0 rgba(34, 197, 94, 0.39)', // untuk class shadow-eco
                },
                'leaf': {
                    light: '#a7f3d0',
                    DEFAULT: '#34d399',
                    dark:  '#059669',
                },
            },
            backgroundImage: {
                'gradient-eco': 'linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%)',
                'gradient-eco-light': 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)',
            },
            boxShadow: {
                'eco':  '0 4px 14px 0 rgba(34, 197, 94, 0.39)',
                'eco-lg': '0 10px 30px 0 rgba(34, 197, 94, 0.3)',
            },
        },
    },

    plugins: [forms],
};
