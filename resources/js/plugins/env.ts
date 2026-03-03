import { Plugin } from "vue";

const env: Plugin = {
    install(app) {
        app.config.globalProperties.$env = { ...window.chat, ...window.chatHandlers };
        app.provide("env", app.config.globalProperties.$env);
    },
};

export default env;
