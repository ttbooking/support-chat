import { Plugin } from "vue";

const env: Plugin = {
    install(app) {
        app.config.globalProperties.$env = window.chat;
        app.provide("env", window.chat);
    },
};

export default env;
