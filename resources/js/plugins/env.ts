import { Plugin } from "vue";

const env: Plugin = {
    install(app) {
        app.config.globalProperties.$env = window.SupportChat;
        app.provide("env", window.SupportChat);
    },
};

export default env;
