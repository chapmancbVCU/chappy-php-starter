import { defineConfig } from 'vite';
import path from 'path'; // ✅ ADD THIS
import FullReload from 'vite-plugin-full-reload';

export default defineConfig({
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            input: {
                main: 'resources/js/app.js',
                styles: 'resources/css/app.css',
            },
        },
    },
    server: {
        origin: 'http://localhost:5173',
        watch: {
            ignored: ['!**/resources/views/**'],
        },
        cors: true,
        fs: {
            allow: ['..', 'node_modules'],
        },
    },
    resolve: {
        alias: {
            tinymce: path.resolve(__dirname, 'node_modules/tinymce') // ✅ This now works
        }
    },
    plugins: [
        FullReload(['resources/views/**/*.php']),
    ],
});
