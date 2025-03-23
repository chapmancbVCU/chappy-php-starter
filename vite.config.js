import { defineConfig } from 'vite';
import FullReload from 'vite-plugin-full-reload';

export default defineConfig({
    build: {
        outDir: 'public/build', // Output directory for production build
        manifest: true,         // Generate a manifest.json for PHP integration
        rollupOptions: {
            input: {
                main: 'resources/js/app.js',  // Your main JavaScript entry point
                styles: 'resources/css/app.css', // Optional CSS entry point
            },
        },
    },
    server: {
        origin: 'http://localhost:5173', // Dev server origin for hot reload
        watch: {
            // Watch for changes in your views directory
            ignored: ['!**/resources/views/**'],
        },
        cors: true,
    },
    plugins: [
        FullReload(['resources/views/**/*.php']), // Watch PHP view files
    ],
});
