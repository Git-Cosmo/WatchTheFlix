/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // GitHub Copilot-inspired dark theme
                'dark': {
                    DEFAULT: '#0d1117',
                    50: '#f6f8fa',
                    100: '#eaeef2',
                    200: '#d0d7de',
                    300: '#afb8c1',
                    400: '#8c959f',
                    500: '#6e7681',
                    600: '#57606a',
                    700: '#424a53',
                    800: '#32383f',
                    900: '#24292f',
                    950: '#0d1117',
                },
                'accent': {
                    DEFAULT: '#1f6feb',
                    50: '#ebf4ff',
                    100: '#d4e8ff',
                    200: '#acd4ff',
                    300: '#7dbdff',
                    400: '#4ca4ff',
                    500: '#1f6feb',
                    600: '#0969da',
                    700: '#0550ae',
                    800: '#033d8b',
                    900: '#0a3069',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
