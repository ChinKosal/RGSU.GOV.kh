import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/admin/js/app.js",
                "resources/admin/sass/app.scss",
                "resources/admin/js/body.js",
                "resources/admin/js/package/iCheck/icheck.min.js",
                "resources/website/js/app.js",
                "resources/website/sass/app.scss",
                "resources/website/js/body.js",
                "resources/website/js/header.js",
            ],

            refresh: true,
        }),
    ],

    css: {
        postCss: {
            plugins: {
                tailwindcss: {},
                autoprefixer: {},
            },
        },
    },
});
