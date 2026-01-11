import { defineConfig } from 'vite';
import path from 'path';
import react from '@vitejs/plugin-react';
import FullReload from 'vite-plugin-full-reload';

export default defineConfig(({ command }) => ({
    // Use a base only for *build* so runtime assets resolve correctly
    base: command === 'build' ? '/public/build/' : '',
    publicDir: false,
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            input: {
                main: 'resources/js/app.jsx',
                styles: 'resources/css/app.css',
            },
        },
    },
    server: {
        origin: 'http://localhost:5173',
        watch: {
            ignored: ['!**/resources/view/**', '!**/resources/views/**'],
        },
        cors: true,
        fs: {
            allow: [
                '..',
                'node_modules',
                path.resolve(__dirname, 'vendor/chappy-php/chappy-php-framework'),
            ]
        },
    },
    resolve: {
        alias: {
            tinymce: path.resolve(__dirname, 'node_modules/tinymce'),
            '@tinymce/tinymce-react': path.resolve(__dirname, 'node_modules/@tinymce/tinymce-react'),
            '@': path.resolve(__dirname, 'resources/js'),
            '@chappy': path.resolve(__dirname, 'vendor/chappy-php/chappy-php-framework/src/React'),
            '@css': path.resolve(__dirname, 'resources/css'),
        }
    },
    plugins: [
        react(),
        FullReload(['resources/view/**/*.php', 'resources/views/**/*.php']),
    ],
    optimizeDeps: {
        include: ['@tinymce/tinymce-react', 'tinymce'],
    },
    test: {
        environment: 'jsdom',
    }
}));
