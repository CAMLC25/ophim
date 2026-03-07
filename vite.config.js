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
    ],
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['hls.js', 'axios'],
                },
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
            port: 5173,
        },
    },
});
