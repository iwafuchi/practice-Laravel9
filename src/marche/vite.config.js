import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/asset/modal/view-image.js',
                'resources/js/asset/alert/delete-alert.js',
                'resources/js/asset/swiper/swiper.js'
            ],
            refresh: true,
        }),
    ],
});
