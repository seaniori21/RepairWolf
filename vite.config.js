import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            'vue': path.resolve(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
        }
    },
    server: {
        // host: '127.0.0.1',
        // port: 8001,
        // hot: true
    }
});
