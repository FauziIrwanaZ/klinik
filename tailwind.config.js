/** @type {import('tailwindcss').Config} */
module.exports = {
    // Scan semua file Blade, JS, dan Livewire
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            // Tambah warna kustom tema klinik
            colors: {
                klinik: {
                    50:  '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                }
            },
            // Font Inter
            fontFamily: {
                inter: ['Inter', 'sans-serif'],
            },
            // Border radius kustom
            borderRadius: {
                'xl':  '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            }
        },
    },
    plugins: [],
}
