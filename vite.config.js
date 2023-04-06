import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
export default defineConfig({
    server: {
        https: true,
        host: "0.0.0.0",
    },
    plugins: [
        basicSsl(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/swiper.js",
            ],
            refresh: true,
        }),
    ],
});
