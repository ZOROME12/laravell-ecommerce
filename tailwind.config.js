import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
         './resources/js/**/*.js',
        './resources/js/**/*.vue'
    ],

    theme: {
    extend: {
        colors: {
            easeDark: "#3F1A2B",
            easeRed: "#B2183A",
            easePink: "#ED4A69",
            easeLight: "#FBF8FB",
        },
        fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        },
    },
},


    plugins: [forms],
};
