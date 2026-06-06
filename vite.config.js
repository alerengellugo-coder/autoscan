import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.tsx',
            ],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@components': path.resolve(__dirname, './resources/js/Components'),
            '@layouts': path.resolve(__dirname, './resources/js/Layouts'),
            '@pages': path.resolve(__dirname, './resources/js/Pages'),
            '@hooks': path.resolve(__dirname, './resources/js/Hooks'),
            '@services': path.resolve(__dirname, './resources/js/Services'),
            '@utils': path.resolve(__dirname, './resources/js/Utils'),
            '@types': path.resolve(__dirname, './resources/js/Types'),
        },
    },
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        sourcemap: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor-react': ['react', 'react-dom'],
                    'vendor-inertia': ['@inertiajs/react', '@inertiajs/node'],
                },
            },
        },
    },
});
