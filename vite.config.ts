import { defineConfig } from "vite";
import i18n from "@intlify/unplugin-vue-i18n/vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import vuetify, { transformAssetUrls } from "vite-plugin-vuetify";
import path from "path";

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 600,
        rollupOptions: {
            output: {
                manualChunks: {
                    "advanced-chat": ["vue-advanced-chat"],
                },
            },
        },
        sourcemap: true,
    },
    define: {
        __VUE_PROD_DEVTOOLS__: true,
    },
    plugins: [
        i18n({
            include: [path.resolve(__dirname, "./resources/js/locales/**")],
        }),
        laravel({
            input: ["resources/js/app.ts", "resources/js/win.ts"],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => tag === "vue-advanced-chat" || tag === "emoji-picker",
                },
                transformAssetUrls,
            },
        }),
        vuetify({
            autoImport: true,
            styles: {
                configFile: "resources/css/settings.scss",
            },
        }),
    ],
});
