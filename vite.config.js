import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ], build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['jquery', 'select2', 'bootstrap', 'datatables.net-bs5', 'sweetalert2'],
                    pdf: ['jspdf', 'jszip', 'pdfmake', 'xlsx'],
                    fancybox: ['@fancyapps/ui'],
                    moment: ['moment', 'moment-timezone'],
                    alpine: ['alpinejs'],
                },
            },
        },
    }, resolve: {
        alias: {
            '$': 'jQuery',
        }
    },
});
