import { defineConfig, loadEnv } from 'vite';
import path from 'path';
import react from '@vitejs/plugin-react';
import FullReload from 'vite-plugin-full-reload';
import chappyHot from './vendor/chappy-php/chappy-php-framework/src/vite/chappyHot.js';

export default defineConfig(({ command, mode }) => {
    // Public URL of the dev server as the *browser* sees it.
    // Set VITE_DEV_SERVER in .env when developing in a VM or container.
    const env = loadEnv(mode, process.cwd(), '');
    const devUrl = new URL(env.VITE_DEV_SERVER || 'http://localhost:5173');
    const isLocal = ['localhost', '127.0.0.1'].includes(devUrl.hostname);

    return {
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
        host: isLocal ? 'localhost' : '0.0.0.0',
        port: Number(devUrl.port) || 5173,
        strictPort: true,
        origin: devUrl.origin,
        hmr: { host: devUrl.hostname },
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
        dedupe: ['dompurify', 'react', 'react-dom', 'jquery', 'jquery-ui-dist'],
        alias: {
            tinymce: path.resolve(__dirname, 'node_modules/tinymce'),
            '@tinymce/tinymce-react': path.resolve(__dirname, 'node_modules/@tinymce/tinymce-react'),
            '@': path.resolve(__dirname, 'resources/js'),
            '@chappy': path.resolve(__dirname, 'vendor/chappy-php/chappy-php-framework/src/React'),
            '@core-css': path.resolve(__dirname, 'vendor/chappy-php/chappy-php-framework/src/css'),
            '@core-js': path.resolve(__dirname, 'vendor/chappy-php/chappy-php-framework/src/js'),
            '@css': path.resolve(__dirname, 'resources/css'),
        }
    },
    plugins: [
        react(),
        chappyHot(),
        FullReload(['resources/view/**/*.php', 'resources/views/**/*.php']),
    ],
    optimizeDeps: {
        include: ['@tinymce/tinymce-react', 'tinymce', 'dompurify', 'jquery', 'jquery-ui-dist/jquery-ui'],
    },
    test: {
        globals: true,
        environment: 'jsdom',
        setupFiles: './setup.js',
        css: true,
    }
    };
});
